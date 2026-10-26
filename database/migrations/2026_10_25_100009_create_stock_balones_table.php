<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_balones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('balon_id')->constrained('balones')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('almacen_id')->constrained('almacenes')->cascadeOnUpdate()->restrictOnDelete();
            $table->unsignedInteger('stock_llenos')->default(0);
            $table->unsignedInteger('stock_vacios')->default(0);
            $table->timestamps();

            $table->unique(['balon_id', 'almacen_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_balones');
    }
};
