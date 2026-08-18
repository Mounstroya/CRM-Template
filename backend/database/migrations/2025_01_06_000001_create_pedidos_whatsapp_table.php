<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Written directly by the Node bot (mysql2) when a customer orders from the
        // WhatsApp catalog. Never auto-becomes a real sale — staff confirms from the
        // CRM's WhatsApp section, which reuses VentaController::registroVenta.
        Schema::create('pedidos_whatsapp', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id');
            $table->json('items');
            $table->decimal('total', 12, 2);
            $table->enum('estado', ['pendiente', 'confirmado', 'enviado', 'completado', 'cancelado'])->default('pendiente');
            $table->unsignedBigInteger('venta_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos_whatsapp');
    }
};
