<?php

namespace App\Console\Commands;

use App\Models\AuditoriaConteo;
use App\Models\AuditoriaEvento;
use App\Models\Producto;
use Illuminate\Console\Command;

class BackfillAuditoriasHistorico extends Command
{
    protected $signature = 'app:backfill-auditorias-historico';

    protected $description = 'Import the 118 real historical auditoria events + their 4578 real '
        .'product-count detail rows, pulled live from the original site (database/seed-data/'
        .'auditorias_historico.json). Run app:backfill-productos-por-local first — producto_id here '
        .'resolves against each event\'s local\'s own producto rows.';

    public function handle(): int
    {
        $localesMap = [];
        foreach ($this->csvRows('locales_id_map.csv') as $r) {
            $localesMap[(int) $r['real_id']] = (int) $r['our_id'];
        }

        $path = database_path('seed-data/auditorias_historico.json');
        $eventos = json_decode(file_get_contents($path), true);

        $eventosCreados = 0;
        $conteosCreados = 0;
        $sinProducto = 0;

        foreach ($eventos as $ev) {
            $localId = $localesMap[(int) $ev['local_id']] ?? null;
            if ($localId === null) {
                $this->warn("evento {$ev['auditoria_id']}: local real {$ev['local_id']} sin mapeo, omitido");

                continue;
            }

            $evento = AuditoriaEvento::firstOrCreate(
                ['local_id' => $localId, 'no_auditoria' => $ev['no_auditoria'] ?? (string) $ev['auditoria_id']],
                [
                    'auditor_nombre' => $ev['auditor'] ?? null,
                    'fecha_inicio' => $ev['fecha_inicio'] ?: null,
                    'fecha_fin' => $ev['fecha_fin'] ?: null,
                ]
            );
            if ($evento->wasRecentlyCreated) {
                $eventosCreados++;
            } else {
                continue; // ya importado antes, no duplicar sus conteos
            }

            foreach ($ev['detalle'] as $d) {
                $productoId = null;
                if ($d['clave']) {
                    $productoId = Producto::where('locales_id', $localId)->where('clave', $d['clave'])->value('id');
                }
                if ($productoId === null) {
                    $sinProducto++;
                }

                AuditoriaConteo::create([
                    'auditoria_id' => $evento->id,
                    'no_auditoria' => $ev['no_auditoria'] ?? null,
                    'producto_id' => $productoId,
                    'clave' => $d['clave'],
                    'stock_sistema' => $this->num($d['stock_inicial']) ?? 0,
                    'entradas' => $this->num($d['entradas']),
                    'salidas' => $this->num($d['salidas']),
                    'calculado' => $this->num($d['calculado']),
                    'stock_contado' => $this->num($d['contado']),
                    'diferencia' => $this->num($d['diferencia']),
                    'costo_venta' => $this->num($d['costo_venta']),
                    'comentario' => $d['comentario'] ?: null,
                ]);
                $conteosCreados++;
            }
        }

        $this->info("auditoria_eventos creados: $eventosCreados");
        $this->info("auditoria_conteos creados: $conteosCreados");
        if ($sinProducto) {
            $this->warn("conteos sin producto_id resuelto (clave sin match en ese local): $sinProducto");
        }

        return self::SUCCESS;
    }

    private function num($v): ?float
    {
        return ($v === null || $v === '') ? null : (float) $v;
    }

    private function csvRows(string $file): array
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
