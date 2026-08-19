<?php

namespace App\Console\Commands;

use App\Models\Venta;
use Illuminate\Console\Command;

class BackfillVentasClienteId extends Command
{
    protected $signature = 'app:backfill-ventas-cliente-id';

    protected $description = 'Backfill ventas.cliente_id for the specific ventas that garantías\' '
        ."\"Ver Última Compra\" needs (real historical ventas.csv never captured which client made a "
        .'sale — confirmed a real limitation, /historico never showed that column either). Resolved by '
        .'querying the still-live original site\'s own /garantia/lastPurchase for each of our 363 '
        .'garantías with a cliente_id, then matching the real fecha/producto/cantidad it returns back '
        .'against our already-imported ventas (database/seed-data/garantia_ultima_compra.json).';

    public function handle(): int
    {
        $rows = json_decode(file_get_contents(database_path('seed-data/garantia_ultima_compra.json')), true);

        $matched = 0;
        $alreadySet = 0;
        $noMatch = [];

        foreach ($rows as $r) {
            $ventas = Venta::where('fecha_compra', $r['fecha'])->get();

            $venta = $ventas->first(function ($v) use ($r) {
                return collect($v->producto_vendido ?? [])->contains(
                    fn ($item) => trim($item['nombre'] ?? '') === trim($r['producto'])
                        && (float) ($item['cantidad'] ?? 0) === (float) $r['cantidad']
                );
            });

            if (! $venta) {
                $noMatch[] = $r['garantia_id'];

                continue;
            }

            if ($venta->cliente_id) {
                $alreadySet++;

                continue;
            }

            $venta->cliente_id = (int) $r['cliente_id'];
            $venta->save();
            $matched++;
        }

        $this->info("ventas.cliente_id backfilled: $matched");
        $this->info("ya tenían cliente_id (omitidas): $alreadySet");
        if ($noMatch) {
            $this->warn('garantías sin venta correspondiente encontrada: '.count($noMatch));
        }

        return self::SUCCESS;
    }
}
