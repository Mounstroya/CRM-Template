<?php

namespace App\Console\Commands;

use App\Models\Producto;
use Illuminate\Console\Command;

class RemoveTestProduct extends Command
{
    protected $signature = 'app:remove-test-product';

    protected $description = 'Removes the disposable ZZTEST product row created during Phase 2 verification';

    public function handle(): int
    {
        $deleted = Producto::where('clave', 'ZZTEST')->delete();
        $this->info("deleted: $deleted");

        return self::SUCCESS;
    }
}
