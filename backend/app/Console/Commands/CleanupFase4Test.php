<?php

namespace App\Console\Commands;

use App\Models\PedidoWhatsapp;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Console\Command;

class CleanupFase4Test extends Command
{
    protected $signature = 'app:cleanup-fase4-test';

    protected $description = 'Revert the WA-1 test pedido, its resulting venta, and the stock it moved';

    public function handle(): int
    {
        $pedido = PedidoWhatsapp::find(1);
        if (! $pedido) {
            $this->info('nada que limpiar');

            return self::SUCCESS;
        }

        if ($pedido->venta_id) {
            $venta = Venta::find($pedido->venta_id);
            if ($venta) {
                foreach ($venta->producto_vendido ?? [] as $item) {
                    Producto::where('id', $item['id'])->increment('stock', (float) $item['cantidad']);
                }
                $venta->delete();
                $this->info("venta {$pedido->venta_id} eliminada, stock restaurado");
            }
        }

        $pedido->delete();
        $this->info('pedido WA-1 eliminado');

        return self::SUCCESS;
    }
}
