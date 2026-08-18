<?php

namespace App\Console\Commands;

use App\Models\Caja;
use App\Models\Cliente;
use App\Models\Credito;
use App\Models\Departamento;
use App\Models\Garantia;
use App\Models\Proveedor;
use Illuminate\Console\Command;

/**
 * Removes disposable rows created by the Playwright E2E suite running against the
 * real app (necessary since headless click-through testing needs a real backend),
 * and reverts the two pre-existing real rows it mutates (garantía #2 status,
 * crédito #61 monto_pagado) back to their original values.
 */
class CleanupE2eArtifacts extends Command
{
    protected $signature = 'app:cleanup-e2e';

    protected $description = 'Delete PLAYWRIGHT TEST rows and revert mutated real rows after E2E runs';

    public function handle(): int
    {
        $c1 = Cliente::where('nombre', 'like', 'PLAYWRIGHT TEST%')->delete();
        $c2 = Proveedor::where('nombre', 'like', 'PLAYWRIGHT TEST%')->delete();
        $c3 = Departamento::where('departamento', 'like', 'PLAYWRIGHT TEST%')->delete();
        $c4 = Caja::where('users_id', 16)->where('cuenta_inicial', 0)->delete();

        $garantia = Garantia::find(2);
        if ($garantia && $garantia->status !== 'Solicitud de garantía') {
            $garantia->status = 'Solicitud de garantía';
            $garantia->save();
        }

        $credito = Credito::find(61);
        if ($credito && (float) $credito->monto_pagado !== 0.0) {
            $credito->monto_pagado = null;
            $credito->save();
        }

        $this->info("clientes:$c1 proveedores:$c2 departamentos:$c3 cajas:$c4 — garantia/credito reverted");

        return self::SUCCESS;
    }
}
