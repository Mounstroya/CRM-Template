<?php

namespace App\Console\Commands;

use App\Models\Deposito;
use Illuminate\Console\Command;

class SeedDepositos extends Command
{
    protected $signature = 'app:seed-depositos';

    protected $description = 'Reload the depositos table from database/seed-data/depositos.csv';

    public function handle(): int
    {
        Deposito::query()->delete();

        $path = database_path('seed-data/depositos.csv');
        $fh = fopen($path, 'r');
        $header = fgetcsv($fh);
        $n = 0;
        while (($row = fgetcsv($fh)) !== false) {
            $c = min(count($header), count($row));
            $r = array_combine(array_slice($header, 0, $c), array_slice($row, 0, $c));
            Deposito::create([
                'user_id' => ($r['user_id'] ?? '') !== '' ? (int) $r['user_id'] : null,
                'monto' => ($r['monto'] ?? '') !== '' ? (float) $r['monto'] : null,
                'fecha' => $r['fecha'] ?: null,
                'comprobante' => $r['comprobante'] ?? null,
                'user_name' => $r['user.name'] ?? null,
                'user_email' => $r['user.email'] ?? null,
            ]);
            $n++;
        }
        fclose($fh);

        $this->info("depositos seeded: $n");
        $this->info('depositos in db: '.Deposito::count());

        return self::SUCCESS;
    }
}
