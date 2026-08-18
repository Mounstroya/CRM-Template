<?php

namespace App\Console\Commands;

use App\Models\Categoria;
use App\Models\Departamento;
use App\Models\Producto;
use App\Models\Servicio;
use App\Models\UnidadCompra;
use App\Models\UnidadVenta;
use Illuminate\Console\Command;

/**
 * One-off recovery command (same pattern as the project's other app:backfill-* /
 * app:repair-* commands): the productos already seeded by DatabaseSeeder predate the
 * 2026_08_18_000001 migration's new columns (factor, neto, servicio, unidad_mayoreo_2/3/4,
 * unidad_compra_id/unidad_venta_id), so this backfills them from the SAME real CSV export
 * DatabaseSeeder already uses (database/seed-data/productos.csv), matched by clave — not
 * a re-seed, so it's safe to run against the live 174-producto baseline without
 * duplicating rows. Also creates the unidades_compra/unidades_venta catalog rows using
 * the CSV's own real ids (unidad_compra.id / unidad_venta.id), and backfills categoria_id
 * from database/seed-data/productos_categorias.csv (clave -> categoria name); categoria
 * ids are NOT the real production ids (no categorias.csv with real ids was rescued), so
 * a fresh Categoria catalog is created here under one default "GENERAL" departamento —
 * documented simplification, not fabricated product data.
 */
class BackfillProductoFullFields extends Command
{
    protected $signature = 'app:backfill-producto-full-fields';

    protected $description = 'Backfill factor/neto/servicio/unidad_mayoreo_2-4/unidad_compra_id/unidad_venta_id/categoria_id on productos from the real CSV export';

    public function handle(): int
    {
        $dataDir = database_path('seed-data');

        $categoriaPorClave = [];
        $path = $dataDir.'/productos_categorias.csv';
        if (file_exists($path)) {
            $handle = fopen($path, 'r');
            $header = fgetcsv($handle);
            while ($row = fgetcsv($handle)) {
                $r = array_combine($header, $row);
                $categoriaPorClave[$r['clave']] = $r['categoria'];
            }
            fclose($handle);
        }

        $departamento = Departamento::firstOrCreate(['departamento' => 'GENERAL'], ['status' => 1]);
        $categoriaIdMap = []; // csv categorias_id => local Categoria id

        $handle = fopen($dataDir.'/productos.csv', 'r');
        $header = fgetcsv($handle);
        $n = 0;
        while ($row = fgetcsv($handle)) {
            $r = array_combine($header, $row);
            $clave = $r['clave'] ?? null;
            if (! $clave) {
                continue;
            }
            $producto = Producto::where('clave', $clave)->first();
            if (! $producto) {
                continue;
            }

            $unidadCompraId = null;
            if (! empty($r['unidad_compra.id']) && ! empty($r['unidad_compra.unidad_compra'])) {
                $unidad = UnidadCompra::firstOrCreate(
                    ['id' => (int) $r['unidad_compra.id']],
                    ['unidad_compra' => $r['unidad_compra.unidad_compra']]
                );
                $unidadCompraId = $unidad->id;
            }

            $unidadVentaId = null;
            if (! empty($r['unidad_venta.id']) && ! empty($r['unidad_venta.unidad_venta'])) {
                $unidad = UnidadVenta::firstOrCreate(
                    ['id' => (int) $r['unidad_venta.id']],
                    ['unidad_venta' => $r['unidad_venta.unidad_venta']]
                );
                $unidadVentaId = $unidad->id;
            }

            $categoriaId = null;
            $csvCategoriaId = $r['categorias_id'] ?? null;
            $categoriaNombre = $categoriaPorClave[$clave] ?? null;
            if ($csvCategoriaId && $categoriaNombre) {
                if (! isset($categoriaIdMap[$csvCategoriaId])) {
                    $categoria = Categoria::firstOrCreate(
                        ['categoria' => $categoriaNombre],
                        ['departamentos_id' => $departamento->id]
                    );
                    $categoriaIdMap[$csvCategoriaId] = $categoria->id;
                }
                $categoriaId = $categoriaIdMap[$csvCategoriaId];
            }

            $producto->update([
                'factor' => (float) ($r['factor'] ?: 1) ?: 1,
                'neto' => ($r['neto'] ?? '0') === '1',
                'servicio' => ($r['servicio'] ?? '0') === '1',
                'unidad_mayoreo_2' => $r['unidad_mayoreo_2'] ?: null,
                'unidad_mayoreo_3' => $r['unidad_mayoreo_3'] ?: null,
                'unidad_mayoreo_4' => $r['unidad_mayoreo_4'] ?: null,
                'unidad_compra_id' => $unidadCompraId,
                'unidad_venta_id' => $unidadVentaId,
                'categoria_id' => $categoriaId ?? $producto->categoria_id,
            ]);
            $n++;
        }
        fclose($handle);

        $this->info("productos actualizados: $n");
        $this->info('unidades_compra: '.UnidadCompra::count().' | unidades_venta: '.UnidadVenta::count().' | categorias: '.Categoria::count());

        foreach ([
            ['nombre' => 'Recarga Telcel $50', 'monto' => 50],
            ['nombre' => 'Recarga Telcel $100', 'monto' => 100],
            ['nombre' => 'Recarga AT&T $50', 'monto' => 50],
            ['nombre' => 'Pago de recibo CFE', 'monto' => null],
            ['nombre' => 'Pago de recibo de agua', 'monto' => null],
        ] as $s) {
            Servicio::firstOrCreate(['nombre' => $s['nombre']], ['monto' => $s['monto'], 'status' => 1]);
        }
        $this->info('servicios: '.Servicio::count());

        return self::SUCCESS;
    }
}
