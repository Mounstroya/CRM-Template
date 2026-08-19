<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('traspasos', function (Blueprint $table) {
            // Real system tracks a separate user + timestamp per step (creación,
            // envío, recibido) — our `users_id`/`created_at` only ever captured one.
            // Kept as new nullable columns (existing rows have none of this data).
            $table->unsignedBigInteger('enviado_por')->nullable()->after('users_id');
            $table->unsignedBigInteger('recibido_por')->nullable()->after('enviado_por');
            $table->timestamp('fecha_envio')->nullable()->after('enviado_por');
            $table->timestamp('fecha_recibido')->nullable()->after('recibido_por');
        });
    }

    public function down(): void
    {
        Schema::table('traspasos', function (Blueprint $table) {
            $table->dropColumn(['enviado_por', 'recibido_por', 'fecha_envio', 'fecha_recibido']);
        });
    }
};
