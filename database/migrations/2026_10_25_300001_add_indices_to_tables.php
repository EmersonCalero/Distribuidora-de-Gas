<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->index('estado');
            $table->index('created_at');
        });

        Schema::table('rutas_entrega', function (Blueprint $table) {
            $table->index('estado');
            $table->index('zona');
        });

        Schema::table('clientes', function (Blueprint $table) {
            $table->index('zona');
        });

        Schema::table('movimientos_stock', function (Blueprint $table) {
            $table->index('motivo');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropIndex(['estado']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('rutas_entrega', function (Blueprint $table) {
            $table->dropIndex(['estado']);
            $table->dropIndex(['zona']);
        });

        Schema::table('clientes', function (Blueprint $table) {
            $table->dropIndex(['zona']);
        });

        Schema::table('movimientos_stock', function (Blueprint $table) {
            $table->dropIndex(['motivo']);
            $table->dropIndex(['created_at']);
        });
    }
};
