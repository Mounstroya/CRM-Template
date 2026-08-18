<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('garantias', function (Blueprint $table) {
            $table->unsignedBigInteger('cliente_id')->nullable()->after('cliente');
        });
    }

    public function down(): void
    {
        Schema::table('garantias', function (Blueprint $table) {
            $table->dropColumn('cliente_id');
        });
    }
};
