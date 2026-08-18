<?php

namespace App\Console\Commands;

use App\Models\Venta;
use Illuminate\Console\Command;

/**
 * Re-imports vendedores/departamentos/producto_vendido for already-seeded ventas from
 * ventas.csv, now that the fgetcsv() escape-char bug is fixed (see DatabaseSeeder::rows
 * and the 2026-08-18 comment there) — 1,015 of 5,626 rows had this data silently
 * truncated by the old parser wherever a product name contained an embedded quote
 * (e.g. an inch mark). Matches by no_venta (unique per sale in the source data) and
 * only touches rows whose current value doesn't match a fresh, correct parse — doesn't
 * touch anything already correct or created by this app itself (registroVenta etc.,
 * which never came from this CSV).
 */
class RepairVentasJsonFromCsv extends Command
{
    protected $signature = 'app:repair-ventas-json-from-csv';

    protected $description = 'Re-import vendedores/departamentos/producto_vendido from ventas.csv with the fixed CSV parser';

    public function handle(): int
    {
        $path = database_path('seed-data/ventas.csv');
        $fh = fopen($path, 'r');
        $header = fgetcsv($fh, escape: '');

        $byNoVenta = [];
        while (($row = fgetcsv($fh, escape: '')) !== false) {
            $n = min(count($header), count($row));
            $r = array_combine(array_slice($header, 0, $n), array_slice($row, 0, $n));
            if (! empty($r['no_venta'])) {
                $byNoVenta[(int) $r['no_venta']] = $r;
            }
        }
        fclose($fh);

        $fixed = 0;
        $notFound = 0;
        Venta::whereNotNull('no_venta')->chunkById(200, function ($ventas) use (&$fixed, &$notFound, $byNoVenta) {
            foreach ($ventas as $v) {
                $r = $byNoVenta[(int) $v->no_venta] ?? null;
                if (! $r) {
                    $notFound++;

                    continue;
                }
                $vendedores = $this->decode($r['local'] ?? null);
                $departamentos = $this->decode($r['departamentos'] ?? null);
                $productoVendido = $this->decode($r['productoVendido'] ?? null);

                $dirty = false;
                if ($vendedores !== null && $vendedores !== $v->vendedores) {
                    $v->vendedores = $vendedores;
                    $dirty = true;
                }
                if ($departamentos !== null && $departamentos !== $v->departamentos) {
                    $v->departamentos = $departamentos;
                    $dirty = true;
                }
                if ($productoVendido !== null && $productoVendido !== $v->producto_vendido) {
                    $v->producto_vendido = $productoVendido;
                    $dirty = true;
                }
                if ($dirty) {
                    $v->save();
                    $fixed++;
                }
            }
        });

        $this->info("ventas actualizadas con datos correctos del CSV: $fixed");
        $this->info("ventas sin no_venta correspondiente en el CSV (no tocadas): $notFound");

        return self::SUCCESS;
    }

    private function decode(?string $v): mixed
    {
        if ($v === null || $v === '') {
            return null;
        }
        $decoded = json_decode($v, true);

        return json_last_error() === JSON_ERROR_NONE ? $decoded : null;
    }
}
