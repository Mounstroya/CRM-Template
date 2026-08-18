<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Real evidence (js/app-inventario.js + jspdv/pedidos.js, captured 2026-08-18): the
     * proveedor-purchase "pedido -> arribo" flow reuses the SAME requisiciones list/detail
     * endpoints as the inter-branch traspasos flow built in Fase 5 (routeGetRequisicionesActivas,
     * routeGetRequisicionesSurtidas, routeVerRequisicion all shared verbatim; both feed
     * setPedidosActivos()/setPedidosSurtidos() in jspdv/pedidos.js, which use a generic
     * "proveedor" display field for both a real proveedor name and a sucursal name).
     * This is why proveedor_id is added directly onto traspasos rather than a parallel
     * "compras_pedidos" table: a compra-a-proveedor is a traspaso row with proveedor_id
     * set and sucursal_origen_id/sucursal_destino_id null.
     */
    public function up(): void
    {
        Schema::table('traspasos', function (Blueprint $table) {
            $table->unsignedBigInteger('proveedor_id')->nullable()->after('sucursal_destino_id');
            $table->decimal('efectivo', 12, 2)->nullable()->after('no_requisicion');
            $table->decimal('transferencia', 12, 2)->nullable()->after('efectivo');
            $table->decimal('tarjeta', 12, 2)->nullable()->after('transferencia');
        });

        Schema::table('traspaso_detalles', function (Blueprint $table) {
            $table->decimal('costo_unitario', 12, 2)->nullable()->after('cantidad_recibida');
            $table->decimal('cantidad_comprada', 12, 2)->nullable()->after('costo_unitario');
        });
    }

    public function down(): void
    {
        Schema::table('traspaso_detalles', function (Blueprint $table) {
            $table->dropColumn(['costo_unitario', 'cantidad_comprada']);
        });
        Schema::table('traspasos', function (Blueprint $table) {
            $table->dropColumn(['proveedor_id', 'efectivo', 'transferencia', 'tarjeta']);
        });
    }
};
