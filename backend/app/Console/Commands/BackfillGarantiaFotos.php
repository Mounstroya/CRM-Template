<?php

namespace App\Console\Commands;

use App\Models\Garantia;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Backfills garantias.foto with the real filename in storage/fotos, recovered from
 * an authenticated snapshot of the live /garantia page saved during Phase 1
 * (database/seed-data/garantia_photos.json — the CSV export only captured the "Ver
 * foto" link TEXT, not the href, so the real filename was lost until now). Matched by
 * registro (unique across all 365 rows, confirmed no collisions). producto/cliente
 * cross-check is normalized (collapsed whitespace + HTML-entity decode) before
 * comparing — the raw scrape has stray double-spaces and un-decoded entities like
 * "&quot;" that don't affect the real match, confirmed by inspecting real mismatches.
 */
class BackfillGarantiaFotos extends Command
{
    protected $signature = 'app:backfill-garantia-fotos';

    protected $description = 'Backfill garantias.foto from the recovered live-page snapshot';

    public function handle(): int
    {
        $path = database_path('seed-data/garantia_photos.json');
        if (! File::exists($path)) {
            $this->error("No existe: $path");

            return self::FAILURE;
        }
        $records = json_decode(File::get($path), true);
        $byRegistro = [];
        foreach ($records as $r) {
            $byRegistro[$r['registro']] = $r;
        }

        $matched = 0;
        $mismatchWarnings = 0;
        $noMatch = 0;
        $filesMissing = 0;
        $fotosDir = public_path('storage/fotos');

        foreach (Garantia::all() as $g) {
            $key = optional($g->registro)->format('Y-m-d H:i:s') ?? (string) $g->registro;
            $rec = $byRegistro[$key] ?? null;
            if (! $rec) {
                $noMatch++;

                continue;
            }
            $normalize = fn ($s) => preg_replace('/\s+/', ' ', html_entity_decode(trim($s ?? ''), ENT_QUOTES));
            if ($normalize($rec['producto']) !== $normalize($g->producto) || $normalize($rec['cliente']) !== $normalize($g->cliente)) {
                $mismatchWarnings++;

                continue;
            }
            $g->foto = $rec['foto'];
            if (! empty($rec['tipo_resolucion'])) {
                $g->tipo_resolucion = $rec['tipo_resolucion'];
            }
            $g->save();
            $matched++;
            if (! File::exists("$fotosDir/{$rec['foto']}")) {
                $filesMissing++;
            }
        }

        $this->info("garantias con foto real asignada: $matched");
        $this->info("sin fila correspondiente en el snapshot (registro no encontrado): $noMatch");
        $this->info("registro coincide pero producto/cliente no (no asignado por seguridad): $mismatchWarnings");
        $this->info("de las asignadas, cuántas apuntan a un archivo que NO existe en storage/fotos todavía: $filesMissing");

        return self::SUCCESS;
    }
}
