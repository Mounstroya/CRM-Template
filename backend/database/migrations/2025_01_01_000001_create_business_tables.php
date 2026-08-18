<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable();
            $table->string('correo')->nullable();
            $table->string('telefono')->nullable();
            $table->string('nivel')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });

        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('clave')->nullable();
            $table->string('clave_alterna')->nullable();
            $table->string('descripcion')->nullable();
            $table->decimal('precio_compra', 12, 2)->nullable();
            $table->decimal('precio_1', 12, 2)->nullable();
            $table->decimal('precio_2', 12, 2)->nullable();
            $table->decimal('precio_3', 12, 2)->nullable();
            $table->decimal('precio_4', 12, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->integer('stock_minimo')->nullable();
            $table->integer('stock_maximo')->nullable();
            $table->string('unidad_compra')->nullable();
            $table->string('unidad_venta')->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->timestamp('fecha_compra')->nullable();
            $table->json('vendedores')->nullable();
            $table->decimal('total', 12, 2)->nullable();
            $table->decimal('utilidad', 12, 2)->nullable();
            $table->integer('no_venta')->nullable();
            $table->unsignedTinyInteger('tipo_venta')->nullable();
            $table->unsignedTinyInteger('status')->nullable();
            $table->json('departamentos')->nullable();
            $table->json('producto_vendido')->nullable();
            $table->timestamps();
        });

        Schema::create('creditos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('venta_id')->nullable();
            $table->timestamp('fecha_venta')->nullable();
            $table->integer('plazo_pago')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->decimal('monto_total', 12, 2)->nullable();
            $table->decimal('monto_pagado', 12, 2)->nullable();
            $table->unsignedTinyInteger('estado_pago')->nullable();
            $table->string('cliente_nombre')->nullable();
            $table->integer('no_venta')->nullable();
            $table->timestamps();
        });

        Schema::create('garantias', function (Blueprint $table) {
            $table->id();
            $table->timestamp('registro')->nullable();
            $table->string('abierto_por')->nullable();
            $table->string('producto')->nullable();
            $table->string('cliente')->nullable();
            $table->text('motivo')->nullable();
            $table->string('status')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });

        Schema::create('auditorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('direccion')->nullable();
            $table->string('correo')->nullable();
            $table->string('telefono')->nullable();
            $table->unsignedTinyInteger('status')->nullable();
            $table->timestamp('ultima_auditoria_inicio')->nullable();
            $table->timestamp('ultima_auditoria_fin')->nullable();
            $table->timestamps();
        });

        Schema::create('cajas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('locales_id')->nullable();
            $table->unsignedBigInteger('users_id')->nullable();
            $table->string('turno')->nullable();
            $table->decimal('cuenta_inicial', 12, 2)->nullable();
            $table->decimal('cuenta_final', 12, 2)->nullable();
            $table->timestamp('fecha_apertura')->nullable();
            $table->timestamp('fecha_cierre')->nullable();
            $table->unsignedTinyInteger('status')->nullable();
            $table->json('transacciones')->nullable();
            $table->timestamps();
        });

        Schema::create('depositos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->decimal('monto', 12, 2)->nullable();
            $table->date('fecha')->nullable();
            $table->string('comprobante')->nullable();
            $table->string('user_name')->nullable();
            $table->string('user_email')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('depositos');
        Schema::dropIfExists('cajas');
        Schema::dropIfExists('auditorias');
        Schema::dropIfExists('garantias');
        Schema::dropIfExists('creditos');
        Schema::dropIfExists('ventas');
        Schema::dropIfExists('productos');
        Schema::dropIfExists('clientes');
    }
};
