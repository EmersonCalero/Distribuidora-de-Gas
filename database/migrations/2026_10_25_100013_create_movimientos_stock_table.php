<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('balon_id')->constrained('balones')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('almacen_id')->constrained('almacenes')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('proveedor_id')->nullable()->constrained('proveedores')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('pedido_id')->nullable()->constrained('pedidos')->cascadeOnUpdate()->restrictOnDelete();
            $table->enum('tipo', ['entrada', 'salida', 'ajuste']);
            $table->enum('estado_balon', ['lleno', 'vacio']);
            $table->unsignedInteger('cantidad');
            $table->enum('motivo', ['compra', 'pedido', 'devolucion', 'ajuste']);
            $table->string('observacion', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_stock');
    }
};
