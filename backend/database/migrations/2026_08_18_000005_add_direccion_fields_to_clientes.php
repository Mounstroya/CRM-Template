<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('direccion')->nullable()->after('correo');
            $table->string('municipio')->nullable()->after('direccion');
            $table->string('colonia')->nullable()->after('municipio');
        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn(['direccion', 'municipio', 'colonia']);
        });
    }
};
