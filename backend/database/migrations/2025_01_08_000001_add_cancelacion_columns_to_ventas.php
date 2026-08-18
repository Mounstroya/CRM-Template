<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->text('motivo_cancelacion')->nullable();
            $table->timestamp('fecha_cancelacion')->nullable();
            $table->string('usuario_cancelacion')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn(['motivo_cancelacion', 'fecha_cancelacion', 'usuario_cancelacion']);
        });
    }
};
