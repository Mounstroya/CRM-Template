<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Inter-branch transfer request + shipment tracking, combined into one
        // status-machine model (the real system splits "solicitud" and "movimiento
        // mercancía" into two linked concepts; simplified here into one row per
        // transfer with a richer status list covering both phases).
        // status: 0=Solicitado 1=Autorizado 2=Enviado 3=Recibido 4=Rechazado 5=Cancelado
        Schema::create('traspasos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sucursal_origen_id')->nullable();
            $table->unsignedBigInteger('sucursal_destino_id')->nullable();
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedBigInteger('users_id')->nullable();
            $table->string('no_requisicion')->nullable();
            $table->timestamps();
        });

        Schema::create('traspaso_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('traspaso_id');
            $table->unsignedBigInteger('producto_id');
            $table->decimal('cantidad_solicitada', 12, 2)->default(0);
            $table->decimal('cantidad_enviada', 12, 2)->nullable();
            $table->decimal('cantidad_recibida', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('traspaso_detalles');
        Schema::dropIfExists('traspasos');
    }
};
