<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('auditorias', function (Blueprint $table) {
            // These were already in the rescued CSV (auditoria_sucursales.csv) but not
            // used by the Fase 1 seeder — needed to replicate the real /auditoria
            // listing exactly (parent_id groups branches under a parent, the
            // ultima_auditoria_* fields drive the GENERAR/CONTINUAR button logic).
            $table->unsignedBigInteger('parent_id')->nullable()->after('id');
            $table->unsignedBigInteger('ultima_auditoria_id')->nullable()->after('ultima_auditoria_fin');
            $table->string('ultima_auditoria_no')->nullable()->after('ultima_auditoria_id');
        });
    }

    public function down(): void
    {
        Schema::table('auditorias', function (Blueprint $table) {
            $table->dropColumn(['parent_id', 'ultima_auditoria_id', 'ultima_auditoria_no']);
        });
    }
};
