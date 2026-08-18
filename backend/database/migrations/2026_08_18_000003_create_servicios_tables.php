<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Real routes confirmed from js/app-pdv.js and js/app-caja.js (captured 2026-08-18):
     * routeGetServicios / routeCobroServicio (punto-de-venta's #formCobroServicio, real
     * fields servicio_id + efectivo + transferencia + referencia — NOT a free amount,
     * confirming servicios are a fixed catalog) and routeServiciosRecaudacion /
     * routeFinalizarRecaudacion (caja's "RECAUDACIÓN PAGO SERVICIOS", real response shape
     * {cobros:[{id,efectivo,transferencia,cantidad_retiro,cantidad_diferencia,fecha_pago,
     * servicio:{nombre}}]}).
     */
    public function up(): void
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->decimal('monto', 12, 2)->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('servicio_cobros', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('servicio_id');
            $table->unsignedBigInteger('caja_id')->nullable();
            $table->unsignedBigInteger('users_id')->nullable();
            $table->decimal('efectivo', 12, 2)->default(0);
            $table->decimal('transferencia', 12, 2)->default(0);
            $table->string('referencia')->nullable();
            $table->timestamp('fecha_pago');
            // 0 = pendiente de recaudación, 1 = recaudado (cerrado en caja)
            $table->unsignedTinyInteger('status')->default(0);
            $table->decimal('cantidad_retiro', 12, 2)->nullable();
            $table->decimal('cantidad_diferencia', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicio_cobros');
        Schema::dropIfExists('servicios');
    }
};
