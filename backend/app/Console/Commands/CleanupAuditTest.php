<?php

namespace App\Console\Commands;

use App\Models\Caja;
use App\Models\User;
use Illuminate\Console\Command;

class CleanupAuditTest extends Command
{
    protected $signature = 'app:cleanup-audit-test';

    protected $description = 'Remove disposable test data created verifying the Fase 5 audit fixes';

    public function handle(): int
    {
        $u = User::where('email', 'test.audit@example.com')->delete();
        $c = Caja::where('id', 8)->where('cuenta_inicial', 500)->delete();
        $this->info("usuarios eliminados: $u, cajas eliminadas: $c");

        return self::SUCCESS;
    }
}
