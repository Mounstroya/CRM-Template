<?php

namespace App\Console\Commands;

use App\Models\Traspaso;
use App\Models\User;
use Illuminate\Console\Command;

class BackfillTraspasosHistorico extends Command
{
    protected $signature = 'app:backfill-traspasos-historico';

    protected $description = 'Import the real movimiento-de-mercancia history (2600 real traspasos) '
        .'pulled live from the original site — the `traspasos` table was previously empty because only '
        .'the forward-going creation logic was built (Fase 3b), never a historical import.';

    // Real status codes confirmed from the live data: 1 = recién solicitado (1 caso
    // real visto), 3 = autorizado pero sin enviar (53 casos, sin fecha_envio/recibido),
    // 7 = Finalizado/recibido (2546 casos, la inmensa mayoría). Mapeados a nuestro enum
    // existente (TraspasoController::SOLICITADO/AUTORIZADO/../RECIBIDO).
    private const STATUS_MAP = [1 => 0, 3 => 1, 7 => 3];

    public function handle(): int
    {
        $localesMap = [];
        foreach ($this->rows('locales_id_map.csv') as $r) {
            $localesMap[(int) $r['real_id']] = (int) $r['our_id'];
        }

        $emailPorRealUserId = [];
        foreach ($this->rows('usuarios_real_id_email.csv') as $r) {
            $emailPorRealUserId[(int) $r['real_id']] = $r['email'];
        }
        $ourUserIdPorEmail = User::pluck('id', 'email')->all();

        $resolveUser = function (?string $realIdStr) use ($emailPorRealUserId, $ourUserIdPorEmail) {
            if (! $realIdStr) {
                return null;
            }
            $email = $emailPorRealUserId[(int) $realIdStr] ?? null;

            return $email ? ($ourUserIdPorEmail[$email] ?? null) : null;
        };

        $created = 0;
        $skippedExisting = 0;
        $skippedNoLocal = 0;
        $unknownStatus = [];

        foreach ($this->rows('traspasos_historico.csv') as $r) {
            if (Traspaso::where('no_requisicion', $r['no_traspaso'])->exists()) {
                $skippedExisting++;

                continue;
            }

            $origenReal = (int) $r['local_origen_id'];
            $destinoReal = (int) $r['local_destino_id'];
            $origen = $localesMap[$origenReal] ?? null;
            $destino = $localesMap[$destinoReal] ?? null;
            if ($origen === null || $destino === null) {
                $skippedNoLocal++;

                continue;
            }

            $realStatus = (int) $r['status'];
            if (! isset(self::STATUS_MAP[$realStatus])) {
                $unknownStatus[$realStatus] = ($unknownStatus[$realStatus] ?? 0) + 1;
            }
            $status = self::STATUS_MAP[$realStatus] ?? 3;

            Traspaso::create([
                'sucursal_origen_id' => $origen,
                'sucursal_destino_id' => $destino,
                'status' => $status,
                'users_id' => $resolveUser($r['creado_por']),
                'enviado_por' => $resolveUser($r['enviado_por']),
                'recibido_por' => $resolveUser($r['recibido_por']),
                'no_requisicion' => $r['no_traspaso'],
                'created_at' => $r['fecha_creacion'] ?: null,
                'fecha_envio' => $r['fecha_envio'] ?: null,
                'fecha_recibido' => $r['fecha_recibido'] ?: null,
            ]);
            $created++;
        }

        $this->info("traspasos creados: $created");
        $this->info("ya existentes (omitidos): $skippedExisting");
        if ($skippedNoLocal) {
            $this->warn("sin local mapeado (omitidos): $skippedNoLocal");
        }
        if ($unknownStatus) {
            $this->warn('status reales sin mapeo explicito (usado default=3/Recibido): '.json_encode($unknownStatus));
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
