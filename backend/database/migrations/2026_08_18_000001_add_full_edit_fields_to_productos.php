<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Real fields confirmed from js/app-inventario.js's editProducto/form_edit_productos
     * (unminified jspdv/pedidos.js + minified app-inventario.js, captured 2026-08-18):
     * factor, neto, unidad_mayoreo_2/3/4, servicio, and unidad_compra_id/unidad_venta_id
     * (real FK selects backed by their own small catalog tables — unidades_compra /
     * unidades_venta — not just the free-text unidad_compra/unidad_venta strings this
     * build already had). Both are kept: the FK id drives the real edit form/select2,
     * the plain string columns stay in sync for the existing DataTables display code
     * in ProductoController::all() (untouched, no need to rewrite it to join).
     */
    public function up(): void
    {
        Schema::create('unidades_compra', function (Blueprint $table) {
            $table->id();
            $table->string('unidad_compra');
            $table->timestamps();
        });

        Schema::create('unidades_venta', function (Blueprint $table) {
            $table->id();
            $table->string('unidad_venta');
            $table->timestamps();
        });

        Schema::table('productos', function (Blueprint $table) {
            $table->unsignedBigInteger('unidad_compra_id')->nullable()->after('unidad_compra');
            $table->unsignedBigInteger('unidad_venta_id')->nullable()->after('unidad_venta');
            $table->decimal('factor', 12, 2)->default(1)->after('unidad_venta_id');
            $table->boolean('neto')->default(false)->after('factor');
            $table->boolean('servicio')->default(false)->after('neto');
            $table->string('unidad_mayoreo_2')->nullable()->after('precio_2');
            $table->string('unidad_mayoreo_3')->nullable()->after('precio_3');
            $table->string('unidad_mayoreo_4')->nullable()->after('precio_4');
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn([
                'unidad_compra_id', 'unidad_venta_id', 'factor', 'neto', 'servicio',
                'unidad_mayoreo_2', 'unidad_mayoreo_3', 'unidad_mayoreo_4',
            ]);
        });
        Schema::dropIfExists('unidades_venta');
        Schema::dropIfExists('unidades_compra');
    }
};
