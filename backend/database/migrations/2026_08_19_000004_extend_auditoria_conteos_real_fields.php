<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Clear the single stale test row from the Fase 7 "own design" attempt —
        // `auditoria_id` there pointed at the old ultima_auditoria concept, not the
        // new auditoria_eventos table this migration prepares it for.
        DB::table('auditoria_conteos')->truncate();

        Schema::table('auditoria_conteos', function (Blueprint $table) {
            $table->string('clave')->nullable()->after('producto_id');
            $table->decimal('entradas', 12, 2)->nullable()->after('stock_sistema');
            $table->decimal('salidas', 12, 2)->nullable()->after('entradas');
            $table->decimal('calculado', 12, 2)->nullable()->after('salidas');
            $table->decimal('costo_venta', 12, 2)->nullable()->after('diferencia');
            $table->text('comentario')->nullable()->after('costo_venta');
            $table->unsignedBigInteger('producto_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('auditoria_conteos', function (Blueprint $table) {
            $table->dropColumn(['clave', 'entradas', 'salidas', 'calculado', 'costo_venta', 'comentario']);
        });
    }
};
