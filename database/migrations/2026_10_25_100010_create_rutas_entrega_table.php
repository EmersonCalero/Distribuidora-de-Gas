<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rutas_entrega', function (Blueprint $table) {
            $table->id();
            $table->string('numero_ruta', 20)->unique();
            $table->string('zona', 100);
            $table->text('ubicaciones_incluidas');
            $table->foreignId('vehiculo_id')->nullable()->constrained('vehiculos')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('empleado_id')->nullable()->constrained('empleados')->cascadeOnUpdate()->restrictOnDelete();
            $table->enum('estado', ['planificada', 'en_transito', 'completada', 'suspendida'])->default('planificada');
            $table->dateTime('fecha_inicio')->nullable();
            $table->dateTime('fecha_fin')->nullable();
            $table->unsignedInteger('pedidos_planificados')->default(0);
            $table->unsignedInteger('pedidos_completados')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rutas_entrega');
    }
};
