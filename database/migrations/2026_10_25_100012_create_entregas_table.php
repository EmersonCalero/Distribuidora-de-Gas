<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entregas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->unique()->constrained('pedidos')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('ruta_id')->constrained('rutas_entrega')->cascadeOnUpdate()->restrictOnDelete();
            $table->unsignedInteger('balones_entregados');
            $table->unsignedInteger('balones_retornados')->default(0);
            $table->string('observaciones', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entregas');
    }
};
