<?php

namespace App\Console\Commands;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\UnidadCompra;
use App\Models\UnidadVenta;
use Illuminate\Console\Command;

class BackfillProductosPorLocal extends Command
{
    protected $signature = 'app:backfill-productos-por-local';

    protected $description = 'Import each real local\'s (vendedor\'s) own independent producto/stock '
        .'rows from database/seed-data/productos_por_local.csv (real per-local catalogs pulled live '
        .'from the original site — confirmed the real system gives every local its own producto row '
        .'per clave, not one shared catalog). Local 1 (Bodega Principal) is skipped: it already matches '
        .'the 174 productos rows imported in Fase 1, and re-importing it would duplicate every clave.';

    public function handle(): int
    {
        $categoriaNombrePorRealId = [];
        $depJson = json_decode(file_get_contents(database_path('seed-data/departamentos_categorias.json')), true);
        foreach ($depJson['categorias'] as $c) {
            $categoriaNombrePorRealId[$c['id']] = $c['categoria'];
        }
        $categoriaIdCache = [];

        // Real local ids don't match ours (same 27 locales, same order, but the real
        // site has gaps in its id sequence) — translate via the positional mapping
        // captured in locales_id_map.csv (see BackfillTraspasosHistorico for the same
        // pattern).
        $localesMap = [];
        foreach ($this->rows('locales_id_map.csv') as $r) {
            $localesMap[(int) $r['real_id']] = (int) $r['our_id'];
        }

        $created = 0;
        $skippedLocal1 = 0;
        foreach ($this->rows('productos_por_local.csv') as $r) {
            $realLocalId = (int) $r['locales_id'];
            $localesId = $localesMap[$realLocalId] ?? null;
            if ($localesId === null) {
                $this->warn("real locales_id sin mapeo: $realLocalId");

                continue;
            }
            if ($localesId === 1) {
                $skippedLocal1++;
                continue;
            }

            // Idempotent: re-running shouldn't duplicate rows already imported.
            $exists = Producto::where('locales_id', $localesId)->where('clave', $r['clave'])->exists();
            if ($exists) {
                continue;
            }

            $categoriaId = null;
            $realCatId = $r['categorias_id'] ?: null;
            if ($realCatId && isset($categoriaNombrePorRealId[$realCatId])) {
                $nombre = $categoriaNombrePorRealId[$realCatId];
                if (! isset($categoriaIdCache[$nombre])) {
                    $categoriaIdCache[$nombre] = Categoria::where('categoria', $nombre)->value('id');
                }
                $categoriaId = $categoriaIdCache[$nombre];
            }

            $unidadCompraId = null;
            if ($r['unidad_compra_id']) {
                $unidadCompraId = UnidadCompra::find((int) $r['unidad_compra_id'])?->id;
            }
            $unidadVentaId = null;
            if ($r['unidad_venta_id']) {
                $unidadVentaId = UnidadVenta::find((int) $r['unidad_venta_id'])?->id;
            }

            Producto::create([
                'locales_id' => $localesId,
                'clave' => $r['clave'],
                'clave_alterna' => $r['clave_alterna'] ?: null,
                'descripcion' => $r['descripcion'],
                'categoria_id' => $categoriaId,
                'unidad_compra_id' => $unidadCompraId,
                'unidad_venta_id' => $unidadVentaId,
                'factor' => $r['factor'] ?: 1,
                'precio_compra' => $r['precio_compra'] ?: null,
                'precio_1' => $r['precio_1'] ?: null,
                'precio_2' => $r['precio_2'] ?: null,
                'precio_3' => $r['precio_3'] ?: null,
                'precio_4' => $r['precio_4'] ?: null,
                'stock' => (int) ($r['stock'] ?: 0),
                'stock_minimo' => $r['stock_minimo'] !== '' ? (int) $r['stock_minimo'] : null,
                'stock_maximo' => $r['stock_maximo'] !== '' ? (int) $r['stock_maximo'] : null,
                'status' => (int) ($r['status'] ?: 1),
            ]);
            $created++;
        }

        $this->info("productos creados (locales != 1): $created");
        $this->info("filas de local 1 omitidas (ya existen desde Fase 1): $skippedLocal1");

        return self::SUCCESS;
    }

    private function rows(string $file): array
    {
        $path = database_path('seed-data/'.$file);
        $fh = fopen($path, 'r');
        $header = fgetcsv($fh);
        $out = [];
        while (($row = fgetcsv($fh)) !== false) {
            $c = min(count($header), count($row));
            $out[] = array_combine(array_slice($header, 0, $c), array_slice($row, 0, $c));
        }
        fclose($fh);

        return $out;
    }
}
