<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class BackfillUsuariosLocalesId extends Command
{
    protected $signature = 'app:backfill-usuarios-locales-id';

    protected $description = 'Populate usuarios.locales_id (which local/vendedor each user is) from '
        .'database/seed-data/usuarios_locales_id.csv, matched by email against the real site\'s own '
        .'locales_id per user and translated through locales_id_map.csv.';

    public function handle(): int
    {
        $localesMap = [];
        foreach ($this->rows('locales_id_map.csv') as $r) {
            $localesMap[(int) $r['real_id']] = (int) $r['our_id'];
        }

        $updated = 0;
        $unmatched = [];
        foreach ($this->rows('usuarios_locales_id.csv') as $r) {
            if (! $r['locales_id']) {
                continue;
            }
            $ourLocalId = $localesMap[(int) $r['locales_id']] ?? null;
            if ($ourLocalId === null) {
                $unmatched[] = $r['email'];

                continue;
            }
            $n = User::where('email', $r['email'])->update(['locales_id' => $ourLocalId]);
            if ($n) {
                $updated++;
            } else {
                $unmatched[] = $r['email'];
            }
        }

        $this->info("usuarios actualizados: $updated");
        if ($unmatched) {
            $this->warn('emails sin match en usuarios: '.implode(', ', $unmatched));
        }

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
