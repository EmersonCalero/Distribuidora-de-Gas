<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->unique()->constrained('usuarios')->cascadeOnUpdate()->restrictOnDelete();
            $table->enum('tipo_cliente', ['residencial', 'comercial', 'industrial'])->default('residencial');
            $table->boolean('es_frecuente')->default(false);
            $table->string('zona', 100);
            $table->string('direccion', 255);
            $table->string('dia_compra_habitual', 20)->nullable();
            $table->text('notas')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
