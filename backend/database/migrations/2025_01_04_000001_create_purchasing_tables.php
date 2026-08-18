<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('representante')->nullable();
            $table->string('celular')->nullable();
            $table->string('telefono')->nullable();
            $table->string('emails')->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('departamentos', function (Blueprint $table) {
            $table->id();
            $table->string('departamento');
            $table->unsignedTinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('departamentos_id');
            $table->string('categoria');
            $table->timestamps();
        });

        // Simplified single-step stock entry (the real system's full
        // pedido->arribo purchase-order workflow is out of scope for this pass).
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('producto_id');
            $table->unsignedBigInteger('proveedor_id')->nullable();
            $table->decimal('cantidad', 12, 2);
            $table->decimal('costo_unitario', 12, 2)->nullable();
            $table->unsignedBigInteger('users_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compras');
        Schema::dropIfExists('categorias');
        Schema::dropIfExists('departamentos');
        Schema::dropIfExists('proveedores');
    }
};
