<?php

namespace App\Console\Commands;

use App\Models\Auditoria;
use Illuminate\Console\Command;

class BackfillAuditoriaFields extends Command
{
    protected $signature = 'app:backfill-auditoria-fields';

    protected $description = 'Backfill parent_id/ultima_auditoria_id/no from the real auditoria_sucursales.csv (matched by nombre)';

    public function handle(): int
    {
        $path = database_path('seed-data/auditoria_sucursales.csv');
        $fh = fopen($path, 'r');
        $header = fgetcsv($fh, escape: '');
        $byNombre = [];
        while (($row = fgetcsv($fh, escape: '')) !== false) {
            $n = min(count($header), count($row));
            $r = array_combine(array_slice($header, 0, $n), array_slice($row, 0, $n));
            $byNombre[trim($r['nombre'] ?? '')] = $r;
        }
        fclose($fh);

        // Two-pass: first assign each branch its own new id, then resolve parent_id
        // (which references the OLD csv id) to the new local id.
        $oldIdToNewId = [];
        $updates = [];
        foreach (Auditoria::all() as $a) {
            $r = $byNombre[trim($a->nombre ?? '')] ?? null;
            if (! $r) {
                continue;
            }
            $oldIdToNewId[(int) $r['id']] = $a->id;
            $updates[$a->id] = $r;
        }

        $matched = 0;
        foreach ($updates as $newId => $r) {
            $a = Auditoria::find($newId);
            $oldParentId = $r['parent_id'] !== '' ? (int) $r['parent_id'] : null;
            $a->parent_id = $oldParentId ? ($oldIdToNewId[$oldParentId] ?? null) : null;
            $a->ultima_auditoria_id = $r['ultima_auditoria.id'] !== '' ? (int) $r['ultima_auditoria.id'] : null;
            $a->ultima_auditoria_no = $r['ultima_auditoria.no_auditoria'] ?: null;
            $a->save();
            $matched++;
        }

        $this->info("auditorias actualizadas: $matched de ".Auditoria::count());

        return self::SUCCESS;
    }
}
