<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            // The rescued ventas.csv (5,626 historical rows) never captured client
            // identity per sale — /historico never showed a client column. So
            // /garantia/lastPurchase can only find matches for sales made going
            // forward through this clone's POS, not the historical import. Documented
            // limitation, not a bug: there's no real data to backfill this from.
            $table->unsignedBigInteger('cliente_id')->nullable()->after('vendedores');
        });
    }

    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn('cliente_id');
        });
    }
};
