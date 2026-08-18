<?php

namespace Database\Seeders;

use App\Models\Auditoria;
use App\Models\Caja;
use App\Models\Cliente;
use App\Models\Credito;
use App\Models\Deposito;
use App\Models\Garantia;
use App\Models\Producto;
use App\Models\User;
use App\Models\Venta;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    private string $dataDir;

    public function run(): void
    {
        $this->dataDir = database_path('seed-data');

        $this->seedUsuarios();
        $this->seedClientes();
        $this->seedProductos();
        $this->seedVentas();
        $this->seedCreditos();
        $this->backfillClienteCreditInfo();
        $this->seedGarantias();
        $this->seedAuditorias();
        $this->seedCajas();
        $this->seedDepositos();
        $this->seedServicios();
    }

    private function rows(string $file): array
    {
        $path = $this->dataDir.'/'.$file;
        if (! file_exists($path)) {
            $this->command?->warn("Seed file missing: $file");
            return [];
        }
        $fh = fopen($path, 'r');
        // Real bug fixed 2026-08-18: PHP's fgetcsv() defaults to '\' as an "escape"
        // character, which has long-standing quirky/non-RFC4180 handling when a
        // quoted field contains a literal backslash-quote sequence (\") — exactly
        // what's inside ventas.csv's JSON-in-CSV columns for any product name with an
        // embedded ", e.g. an inch mark (BOCINA 8\" LINK BITS). That silently
        // truncated/corrupted the field for ~1,015 of 5,626 rows (fgetcsv just stops
        // partway through instead of erroring). Passing '' disables PHP's escape-char
        // special-casing so quoted fields are parsed by the plain RFC4180 doubled-quote
        // rule only, which is what the CSV was actually written with.
        $header = fgetcsv($fh, escape: '');
        $out = [];
        while (($row = fgetcsv($fh, escape: '')) !== false) {
            // Some source pages have malformed HTML (unclosed <td>) which throws off
            // column counts when scraped — map leniently by position instead of skipping.
            $n = min(count($header), count($row));
            $out[] = array_combine(array_slice($header, 0, $n), array_slice($row, 0, $n));
        }
        fclose($fh);
        return $out;
    }

    private function seedUsuarios(): void
    {
        $known = [
            ['name' => 'Administrador', 'email' => 'administrador@fusiond3.mx', 'password' => 'change-me-123', 'type' => 'Propietario'],
            ['name' => 'Gerente', 'email' => 'gerente@fusiond3.mx', 'password' => 'change-me-123', 'type' => 'Encargado'],
        ];

        // Extra users seen embedded in deposit records. Every seeded user gets a
        // placeholder password (hashed below via Hash::make) — real per-employee
        // passwords are managed on the live system, never carried into the seeder.
        foreach ($this->rows($this->depositosFile()) as $r) {
            $email = $r['user.email'] ?? null;
            if (! $email) {
                continue;
            }
            $known[$email] = [
                'name' => $r['user.name'] ?? $email,
                'email' => $email,
                'password' => 'change-me-123',
                'type' => $r['user.type'] ?? 'Vendedor',
            ];
        }

        foreach ($known as $u) {
            User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'password' => Hash::make($u['password']),
                    'type' => $u['type'],
                    'status' => 1,
                ]
            );
        }
        $this->command?->info('usuarios seeded: '.User::count());
    }

    // Real mapping confirmed from cartera-de-clientes.html's window.niveles object.
    private const NIVEL_TEXTO_A_NUMERO = [
        'SUBDISTRIBUIDOR' => 1,
        'PREFERENTE' => 2,
        'POR CAJA' => 3,
    ];

    private function seedClientes(): void
    {
        $n = 0;
        foreach ($this->rows('clientes.csv') as $r) {
            // Source table row had a broken <td> so the "Status" column merged into
            // "Acciones" — infer real status from the action button shown instead
            // ("Activar" means it was Inactivo; "Desactivar" means it was Activo).
            $accionesText = $r['Status'] ?? ($r['Acciones'] ?? '');
            $status = str_contains($accionesText, 'Desactivar') ? 'Activo' : 'Inactivo';
            $nivelTexto = $r['Nivel'] ?? null;

            Cliente::create([
                'nombre' => $r['Nombre'] ?? null,
                'correo' => $r['Correo'] ?? null,
                'telefono' => $r['Teléfono'] ?? null,
                'nivel' => $nivelTexto,
                'nivel_numero' => self::NIVEL_TEXTO_A_NUMERO[strtoupper(trim($nivelTexto ?? ''))] ?? 1,
                'status' => $status,
            ]);
            $n++;
        }
        $this->command?->info("clientes seeded: $n");
    }

    /**
     * creditos.csv embeds the real venta.cliente.dias_credito / limite_credito for the
     * 58 clients who actually took credit. Everyone else genuinely has 0/0.00 in the
     * real system (confirmed live via getCliente on a non-credit client) — that's a
     * real value, not missing data, so it's the correct default.
     */
    private function backfillClienteCreditInfo(): void
    {
        $n = 0;
        foreach ($this->rows('creditos.csv') as $r) {
            $nombre = trim(($r['venta.cliente.nombre'] ?? '').' '.($r['venta.cliente.apellido_p'] ?? '').' '.($r['venta.cliente.apellido_m'] ?? ''));
            $diasCredito = $r['venta.cliente.dias_credito'] ?? null;
            $limiteCredito = $r['venta.cliente.limite_credito'] ?? null;
            if ($nombre === '' || $diasCredito === null) {
                continue;
            }

            $cliente = Cliente::where('nombre', $nombre)->first();
            if (! $cliente) {
                continue;
            }
            $cliente->update([
                'dias_credito' => (int) $diasCredito,
                'limite_credito' => (float) ($limiteCredito ?? 0),
            ]);
            $n++;
        }
        $this->command?->info("clientes con dias_credito/limite_credito reales: $n");
    }

    /**
     * unidad_compra_id/unidad_venta_id use the CSV's own real ids (unidad_compra.id /
     * unidad_venta.id) so every producto referencing the same real unit shares one row.
     * categoria_id has no real-id source (no categorias.csv with real ids was rescued —
     * only a clave->categoria-name mapping in productos_categorias.csv), so a fresh
     * Categoria catalog is created here keyed by name under one default "GENERAL"
     * departamento — documented simplification, not fabricated product data.
     */
    private function seedProductos(): void
    {
        $categoriaPorClave = [];
        foreach ($this->rows('productos_categorias.csv') as $r) {
            $categoriaPorClave[$r['clave']] = $r['categoria'];
        }
        $departamento = \App\Models\Departamento::firstOrCreate(['departamento' => 'GENERAL'], ['status' => 1]);
        $categoriaIdMap = [];

        $n = 0;
        foreach ($this->rows('productos.csv') as $r) {
            $unidadCompraId = null;
            if (! empty($r['unidad_compra.id']) && ! empty($r['unidad_compra.unidad_compra'])) {
                $unidadCompraId = \App\Models\UnidadCompra::firstOrCreate(
                    ['id' => (int) $r['unidad_compra.id']],
                    ['unidad_compra' => $r['unidad_compra.unidad_compra']]
                )->id;
            }
            $unidadVentaId = null;
            if (! empty($r['unidad_venta.id']) && ! empty($r['unidad_venta.unidad_venta'])) {
                $unidadVentaId = \App\Models\UnidadVenta::firstOrCreate(
                    ['id' => (int) $r['unidad_venta.id']],
                    ['unidad_venta' => $r['unidad_venta.unidad_venta']]
                )->id;
            }

            $categoriaId = null;
            $csvCategoriaId = $r['categorias_id'] ?? null;
            $categoriaNombre = $categoriaPorClave[$r['clave'] ?? ''] ?? null;
            if ($csvCategoriaId && $categoriaNombre) {
                if (! isset($categoriaIdMap[$csvCategoriaId])) {
                    $categoriaIdMap[$csvCategoriaId] = \App\Models\Categoria::firstOrCreate(
                        ['categoria' => $categoriaNombre],
                        ['departamentos_id' => $departamento->id]
                    )->id;
                }
                $categoriaId = $categoriaIdMap[$csvCategoriaId];
            }

            Producto::create([
                'clave' => $r['clave'] ?? null,
                'clave_alterna' => $r['clave_alterna'] ?? null,
                'descripcion' => $r['descripcion'] ?? null,
                'precio_compra' => $this->num($r['precio_compra'] ?? null),
                'precio_1' => $this->num($r['precio_1'] ?? null),
                'precio_2' => $this->num($r['precio_2'] ?? null),
                'precio_3' => $this->num($r['precio_3'] ?? null),
                'precio_4' => $this->num($r['precio_4'] ?? null),
                'stock' => (int) ($r['stock'] ?? 0),
                'stock_minimo' => $this->intOrNull($r['stock_minimo'] ?? null),
                'stock_maximo' => $this->intOrNull($r['stock_maximo'] ?? null),
                'unidad_compra' => $r['unidad_compra.unidad_compra'] ?? null,
                'unidad_venta' => $r['unidad_venta.unidad_venta'] ?? null,
                'unidad_compra_id' => $unidadCompraId,
                'unidad_venta_id' => $unidadVentaId,
                'categoria_id' => $categoriaId,
                'factor' => $this->num($r['factor'] ?? null) ?: 1,
                'neto' => ($r['neto'] ?? '0') === '1',
                'servicio' => ($r['servicio'] ?? '0') === '1',
                'unidad_mayoreo_2' => $r['unidad_mayoreo_2'] ?: null,
                'unidad_mayoreo_3' => $r['unidad_mayoreo_3'] ?: null,
                'unidad_mayoreo_4' => $r['unidad_mayoreo_4'] ?: null,
                'status' => (int) ($r['status'] ?? 1),
            ]);
            $n++;
        }
        $this->command?->info("productos seeded: $n");
    }

    /**
     * Own design, documented: no real servicios catalog was rescued (this feature wasn't
     * captured before the original hosting was lost). Seeds a small illustrative set so
     * the real /servicios/* endpoints (punto-de-venta's cobro de servicio, caja's
     * recaudación) have real rows to exercise instead of an empty catalog.
     */
    private function seedServicios(): void
    {
        $servicios = [
            ['nombre' => 'Recarga Telcel $50', 'monto' => 50],
            ['nombre' => 'Recarga Telcel $100', 'monto' => 100],
            ['nombre' => 'Recarga AT&T $50', 'monto' => 50],
            ['nombre' => 'Pago de recibo CFE', 'monto' => null],
            ['nombre' => 'Pago de recibo de agua', 'monto' => null],
        ];
        foreach ($servicios as $s) {
            \App\Models\Servicio::firstOrCreate(['nombre' => $s['nombre']], ['monto' => $s['monto'], 'status' => 1]);
        }
        $this->command?->info('servicios seeded: '.count($servicios));
    }

    private function seedVentas(): void
    {
        $n = 0;
        foreach ($this->rows('ventas.csv') as $r) {
            Venta::create([
                'fecha_compra' => $r['fecha_compra'] ?: null,
                'vendedores' => $this->json($r['local'] ?? null),
                'total' => $this->num($r['total'] ?? null),
                'utilidad' => $this->num($r['utilidad'] ?? null),
                'no_venta' => $this->intOrNull($r['no_venta'] ?? null),
                'tipo_venta' => $this->intOrNull($r['tipo_venta'] ?? null),
                'status' => $this->intOrNull($r['status'] ?? null),
                'departamentos' => $this->json($r['departamentos'] ?? null),
                'producto_vendido' => $this->json($r['productoVendido'] ?? null),
            ]);
            $n++;
        }
        $this->command?->info("ventas seeded: $n");
    }

    private function seedCreditos(): void
    {
        $n = 0;
        foreach ($this->rows('creditos.csv') as $r) {
            $nombre = trim(($r['venta.cliente.nombre'] ?? '').' '.($r['venta.cliente.apellido_p'] ?? '').' '.($r['venta.cliente.apellido_m'] ?? ''));
            Credito::create([
                'venta_id' => $this->intOrNull($r['venta_id'] ?? null),
                'fecha_venta' => $r['fecha_venta'] ?: null,
                'plazo_pago' => $this->intOrNull($r['plazo_pago'] ?? null),
                'fecha_vencimiento' => $r['fecha_vencimiento'] ?: null,
                'monto_total' => $this->num($r['monto_total'] ?? null),
                'monto_pagado' => $this->num($r['monto_pagado'] ?? null),
                'estado_pago' => $this->intOrNull($r['estado_pago'] ?? null),
                'cliente_nombre' => $nombre ?: null,
                'no_venta' => $this->intOrNull($r['venta.no_venta'] ?? null),
            ]);
            $n++;
        }
        $this->command?->info("creditos seeded: $n");
    }

    private function seedGarantias(): void
    {
        // garantias_por_atender.csv is a superset of garantias.csv (same claims, extra
        // "Abierto por" column) — use it as the single source of truth for both views.
        $file = file_exists($this->dataDir.'/garantias_por_atender.csv') ? 'garantias_por_atender.csv' : 'garantias.csv';
        $n = 0;
        foreach ($this->rows($file) as $r) {
            Garantia::create([
                'registro' => $r['Registro'] ?: null,
                'abierto_por' => $r['Abierto por'] ?? null,
                'producto' => $r['Producto'] ?? null,
                'cliente' => $r['Cliente'] ?? null,
                'motivo' => $r['Motivo'] ?? null,
                'status' => $r['Status'] ?? null,
                'foto' => $r['Foto'] ?? null,
            ]);
            $n++;
        }
        $this->command?->info("garantias seeded: $n (from $file)");
    }

    private function seedAuditorias(): void
    {
        $n = 0;
        foreach ($this->rows('auditoria_sucursales.csv') as $r) {
            Auditoria::create([
                'nombre' => $r['nombre'] ?? null,
                'ciudad' => $r['ciudad'] ?? null,
                'direccion' => $r['direccion'] ?? null,
                'correo' => $r['correo'] ?? null,
                'telefono' => $r['telefono'] ?? null,
                'status' => $this->intOrNull($r['status'] ?? null),
                'ultima_auditoria_inicio' => $r['ultima_auditoria.fecha_inicio'] ?: null,
                'ultima_auditoria_fin' => $r['ultima_auditoria.fecha_fin'] ?: null,
            ]);
            $n++;
        }
        $this->command?->info("auditorias seeded: $n");
    }

    private function seedCajas(): void
    {
        $n = 0;
        foreach ($this->rows('cajas.csv') as $r) {
            Caja::create([
                'locales_id' => $this->intOrNull($r['locales_id'] ?? null),
                'users_id' => $this->intOrNull($r['users_id'] ?? null),
                'turno' => $r['turno'] ?? null,
                'cuenta_inicial' => $this->num($r['cuenta_inicial'] ?? null),
                'cuenta_final' => $this->num($r['cuenta_final'] ?? null),
                'fecha_apertura' => $r['fecha_apertura'] ?: null,
                'fecha_cierre' => $r['fecha_cierre'] ?: null,
                'status' => $this->intOrNull($r['status'] ?? null),
                'transacciones' => $this->json($r['transacciones'] ?? null),
            ]);
            $n++;
        }
        $this->command?->info("cajas seeded: $n");
    }

    private function depositosFile(): string
    {
        // Prefer the full history pull if it has content, fall back to the 14-day sample.
        if (file_exists($this->dataDir.'/depositos.csv') && filesize($this->dataDir.'/depositos.csv') > 50) {
            return 'depositos.csv';
        }
        return 'depositos_muestra_14dias.csv';
    }

    private function seedDepositos(): void
    {
        $file = $this->depositosFile();
        $n = 0;
        foreach ($this->rows($file) as $r) {
            Deposito::create([
                'user_id' => $this->intOrNull($r['user_id'] ?? null),
                'monto' => $this->num($r['monto'] ?? null),
                'fecha' => $r['fecha'] ?: null,
                'comprobante' => $r['comprobante'] ?? null,
                'user_name' => $r['user.name'] ?? null,
                'user_email' => $r['user.email'] ?? null,
            ]);
            $n++;
        }
        $this->command?->info("depositos seeded: $n (from $file)");
    }

    private function num(?string $v): ?float
    {
        if ($v === null || $v === '') {
            return null;
        }
        return (float) $v;
    }

    private function intOrNull(?string $v): ?int
    {
        if ($v === null || $v === '') {
            return null;
        }
        return (int) $v;
    }

    /**
     * Real bug fixed 2026-08-18: this used to return the raw CSV string even when it
     * was already valid JSON, on the assumption the model's `array` cast would decode
     * it on write. It doesn't — Eloquent's array cast only *encodes* on write, so
     * handing it an already-JSON string caused it to be encoded AGAIN (double-encoded),
     * leaving columns like ventas.departamentos/producto_vendido holding a JSON STRING
     * scalar instead of a JSON array in the DB. That broke the real rescued JS
     * unmodified in app-ventas.js, which does `t.departamentos.forEach(...)` on the
     * decoded API response — silently throwing and aborting historico's chart
     * rendering. Now returns the *decoded* value so the model cast encodes it exactly
     * once.
     */
    private function json(?string $v): mixed
    {
        if ($v === null || $v === '') {
            return null;
        }
        $decoded = json_decode($v, true);

        return json_last_error() === JSON_ERROR_NONE ? $decoded : $v;
    }
}
