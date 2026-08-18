<?php

namespace App\Console\Commands;

use App\Models\Caja;
use App\Models\Venta;
use Illuminate\Console\Command;

/**
 * One-time repair for a real bug: DatabaseSeeder's old json() helper stored
 * already-JSON CSV text as a raw string, which the model's array cast then
 * double-encoded on write. Fixes existing rows where the column holds a JSON string
 * scalar instead of a real JSON array/object, in place, without touching anything
 * that's already correct (new rows created via crearVenta/registrarMovimiento were
 * never affected — those build real PHP arrays directly).
 */
class RepairDoubleEncodedJson extends Command
{
    protected $signature = 'app:repair-double-encoded-json';

    protected $description = 'Fix ventas/cajas JSON columns that got double-encoded by the old seeder bug';

    public function handle(): int
    {
        $fixedVentas = 0;
        Venta::chunkById(200, function ($ventas) use (&$fixedVentas) {
            foreach ($ventas as $v) {
                $dirty = false;
                foreach (['vendedores', 'departamentos', 'producto_vendido'] as $col) {
                    if (is_string($v->{$col})) {
                        $decoded = json_decode($v->{$col}, true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $v->{$col} = $decoded;
                            $dirty = true;
                        }
                    }
                }
                if ($dirty) {
                    $v->save();
                    $fixedVentas++;
                }
            }
        });

        $fixedCajas = 0;
        foreach (Caja::all() as $c) {
            if (is_string($c->transacciones)) {
                $decoded = json_decode($c->transacciones, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $c->transacciones = $decoded;
                    $c->save();
                    $fixedCajas++;
                }
            }
        }

        $this->info("ventas reparadas: $fixedVentas");
        $this->info("cajas reparadas: $fixedCajas");

        return self::SUCCESS;
    }
}
