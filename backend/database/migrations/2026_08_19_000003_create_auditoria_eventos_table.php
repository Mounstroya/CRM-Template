<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Real historical audit EVENTS — distinct from `auditorias` (which models
        // locales/sucursales themselves). The real site tracks many of these per local
        // over time (Bodega Principal alone has 10); we previously only tracked
        // "última auditoría" as columns directly on `auditorias`.
        Schema::create('auditoria_eventos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('local_id');
            $table->string('no_auditoria')->nullable();
            $table->string('auditor_nombre')->nullable();
            $table->timestamp('fecha_inicio')->nullable();
            $table->timestamp('fecha_fin')->nullable();
            $table->timestamps();

            $table->index('local_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auditoria_eventos');
    }
};
