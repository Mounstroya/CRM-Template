<?php

namespace App\Console\Commands;

use App\Models\Cliente;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Targeted UPDATE-only backfill for the already-seeded 774 clientes rows (does NOT
 * re-run the full seeder, which would duplicate every table via its unconditional
 * create() calls). Safe to run multiple times (idempotent).
 */
class BackfillPhase3 extends Command
{
    protected $signature = 'app:backfill-phase3';

    protected $description = 'Backfill nivel_numero and dias_credito/limite_credito on existing clientes rows';

    private const NIVEL_TEXTO_A_NUMERO = [
        'SUBDISTRIBUIDOR' => 1,
        'PREFERENTE' => 2,
        'POR CAJA' => 3,
    ];

    public function handle(): int
    {
        $n = 0;
        Cliente::chunkById(100, function ($clientes) use (&$n) {
            foreach ($clientes as $c) {
                $numero = self::NIVEL_TEXTO_A_NUMERO[strtoupper(trim($c->nivel ?? ''))] ?? 1;
                if ($c->nivel_numero !== $numero) {
                    $c->nivel_numero = $numero;
                    $c->save();
                    $n++;
                }
            }
        });
        $this->info("nivel_numero actualizado en $n clientes");

        $path = database_path('seed-data/creditos.csv');
        if (! File::exists($path)) {
            $this->warn('creditos.csv no encontrado, se omite backfill de dias_credito/limite_credito');

            return self::SUCCESS;
        }

        $fh = fopen($path, 'r');
        $header = fgetcsv($fh);
        $m = 0;
        while (($row = fgetcsv($fh)) !== false) {
            if (count($row) !== count($header)) {
                continue;
            }
            $r = array_combine($header, $row);
            $nombre = trim(($r['venta.cliente.nombre'] ?? '').' '.($r['venta.cliente.apellido_p'] ?? '').' '.($r['venta.cliente.apellido_m'] ?? ''));
            $diasCredito = $r['venta.cliente.dias_credito'] ?? null;
            if ($nombre === '' || $diasCredito === null) {
                continue;
            }
            $cliente = Cliente::where('nombre', $nombre)->first();
            if (! $cliente) {
                continue;
            }
            $cliente->dias_credito = (int) $diasCredito;
            $cliente->limite_credito = (float) ($r['venta.cliente.limite_credito'] ?? 0);
            $cliente->save();
            $m++;
        }
        fclose($fh);
        $this->info("dias_credito/limite_credito actualizado en $m clientes");

        return self::SUCCESS;
    }
}
