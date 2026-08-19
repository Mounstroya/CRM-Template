<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            // Real system: each "local" (Bodega Principal + one per vendedor) has its
            // own independent set of producto rows/stock, not one shared catalog.
            // Existing 174 rows default to locales_id=1 (Bodega Principal, confirmed
            // exact match against the real catalog) so every already-imported venta/
            // garantia/traspaso_detalle keeps pointing at the right row unchanged.
            $table->unsignedBigInteger('locales_id')->default(1)->after('id');
            $table->index('locales_id');
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('locales_id');
        });
    }
};
