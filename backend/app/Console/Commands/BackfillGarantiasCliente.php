<?php

namespace App\Console\Commands;

use App\Models\Cliente;
use App\Models\Garantia;
use Illuminate\Console\Command;

/**
 * Backfills garantias.cliente_id by exact name match against clientes.nombre — the
 * only link available (garantias.cliente is free text, not a FK, in the rescued data).
 * Ambiguous when >1 cliente shares the exact same name: left null and counted,
 * rather than guessing which one, per the owner's request to document real ambiguity.
 */
class BackfillGarantiasCliente extends Command
{
    protected $signature = 'app:backfill-garantias-cliente';

    protected $description = 'Backfill garantias.cliente_id by exact name match, reporting ambiguous cases';

    public function handle(): int
    {
        $clientesPorNombre = Cliente::all()->groupBy(fn ($c) => trim(mb_strtoupper($c->nombre ?? '')));

        $matched = 0;
        $ambiguous = 0;
        $unmatched = 0;

        Garantia::whereNull('cliente_id')->chunkById(100, function ($garantias) use ($clientesPorNombre, &$matched, &$ambiguous, &$unmatched) {
            foreach ($garantias as $g) {
                $key = trim(mb_strtoupper($g->cliente ?? ''));
                $candidates = $clientesPorNombre->get($key);

                if (! $candidates || $candidates->isEmpty()) {
                    $unmatched++;

                    continue;
                }
                if ($candidates->count() > 1) {
                    $ambiguous++;

                    continue;
                }
                $g->cliente_id = $candidates->first()->id;
                $g->save();
                $matched++;
            }
        });

        $this->info("garantias con cliente_id asignado: $matched");
        $this->info("garantias ambiguas (nombre repetido entre clientes): $ambiguous");
        $this->info("garantias sin cliente que coincida por nombre: $unmatched");

        return self::SUCCESS;
    }
}
