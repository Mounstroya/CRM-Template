<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->unsignedTinyInteger('nivel_numero')->default(1)->after('nivel');
            $table->unsignedInteger('dias_credito')->default(0)->after('nivel_numero');
            $table->decimal('limite_credito', 12, 2)->default(0)->after('dias_credito');
        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn(['nivel_numero', 'dias_credito', 'limite_credito']);
        });
    }
};
