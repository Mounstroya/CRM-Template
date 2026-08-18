<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('garantias', function (Blueprint $table) {
            // Only meaningful when status = 'Resuelto': which of the two real actions
            // applies (real distribution confirmed live: 34 "Hacer Cambio" vs 5 "Usar
            // Nota de Crédito" out of 39 Resuelto rows). Backfilled from a recovered
            // live-page snapshot for existing rows; new rows default to 'cambio' —
            // documented assumption, the real rule that decides which of the two a
            // staff member picks isn't derivable from our data (not simply stock-based,
            // verified: MEMORIA USB16GB had stock=103 and still got nota_credito).
            $table->enum('tipo_resolucion', ['cambio', 'nota_credito'])->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('garantias', function (Blueprint $table) {
            $table->dropColumn('tipo_resolucion');
        });
    }
};
