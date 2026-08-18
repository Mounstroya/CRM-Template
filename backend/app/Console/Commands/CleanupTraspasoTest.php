<?php

namespace App\Console\Commands;

use App\Models\Traspaso;
use Illuminate\Console\Command;

class CleanupTraspasoTest extends Command
{
    protected $signature = 'app:cleanup-traspaso-test';

    protected $description = 'Delete the disposable traspaso #1 created during Fase 3b live verification';

    public function handle(): int
    {
        $t = Traspaso::find(1);
        if ($t && $t->no_requisicion == 1) {
            $t->detalles()->delete();
            $t->delete();
            $this->info('traspaso #1 eliminado');
        } else {
            $this->info('nada que limpiar');
        }

        return self::SUCCESS;
    }
}
