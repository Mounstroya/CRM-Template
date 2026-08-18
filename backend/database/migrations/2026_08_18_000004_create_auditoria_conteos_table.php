<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Own design, documented as such: the real auditoria/{id}/show "toma de auditoría"
     * checklist page could not be reverse-engineered against the still-live original this
     * pass (it requires the real production login, not available in this session — every
     * other page in this project was matched byte-for-byte against a live curl/Playwright
     * session, this one was not). This models the plainly-implied business need (count
     * every product's real stock, compare against system stock, record differences) using
     * the same conventions as the rest of the schema, not fabricated data.
     */
    public function up(): void
    {
        Schema::create('auditoria_conteos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('auditoria_id');
            $table->string('no_auditoria')->nullable();
            $table->unsignedBigInteger('producto_id');
            $table->integer('stock_sistema');
            $table->integer('stock_contado')->nullable();
            $table->integer('diferencia')->nullable();
            $table->unsignedBigInteger('users_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auditoria_conteos');
    }
};
