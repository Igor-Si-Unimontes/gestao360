<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vendas', function (Blueprint $table) {
            $table->id();

            $table->enum('tipo', ['RAPIDA', 'MESA', 'DELIVERY'])->default('RAPIDA');
            $table->enum('status', ['ABERTA', 'FINALIZADA', 'CANCELADA'])->default('FINALIZADA');

            $table->decimal('valor_total', 10, 2)->default(0);

            $table->enum('forma_pagamento', ['DINHEIRO', 'PIX', 'CARTAO'])->nullable();

            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('caixa_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendas');
    }
};
