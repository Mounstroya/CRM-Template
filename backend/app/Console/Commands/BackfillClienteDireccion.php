<?php

namespace App\Console\Commands;

use App\Models\Cliente;
use Illuminate\Console\Command;

class BackfillClienteDireccion extends Command
{
    protected $signature = 'app:backfill-cliente-direccion';

    protected $description = 'Backfill clientes.direccion/municipio/colonia from database/seed-data/clientes_direccion.csv '
        .'(real address data pulled from the original site\'s client export, which the Fase 1 import never captured).';

    public function handle(): int
    {
        $path = database_path('seed-data/clientes_direccion.csv');
        $fh = fopen($path, 'r');
        $header = fgetcsv($fh);
        $rows = [];
        while (($row = fgetcsv($fh)) !== false) {
            $c = min(count($header), count($row));
            $rows[] = array_combine(array_slice($header, 0, $c), array_slice($row, 0, $c));
        }
        fclose($fh);

        $matched = 0;
        $unmatched = [];

        foreach ($rows as $r) {
            $nombre = trim($r['nombre'] ?? '');
            if ($nombre === '') {
                continue;
            }
            if (($r['direccion'] ?? '') === '' && ($r['municipio'] ?? '') === '' && ($r['colonia'] ?? '') === '') {
                continue;
            }

            // Some nombres are legitimately duplicated in the real data — update every
            // matching row, not just the first, so re-running this command stays idempotent.
            $clientes = Cliente::where('nombre', $nombre)->get();
            if ($clientes->isEmpty()) {
                $unmatched[] = $nombre;
                continue;
            }
            foreach ($clientes as $cliente) {
                $cliente->direccion = $r['direccion'] ?: null;
                $cliente->municipio = $r['municipio'] ?: null;
                $cliente->colonia = $r['colonia'] ?: null;
                $cliente->save();
                $matched++;
            }
        }

        $this->info("clientes actualizados: $matched");
        if ($unmatched) {
            $this->warn('nombres sin match en clientes: '.count($unmatched));
            $this->line(implode("\n", $unmatched));
        }

        return self::SUCCESS;
    }
}
