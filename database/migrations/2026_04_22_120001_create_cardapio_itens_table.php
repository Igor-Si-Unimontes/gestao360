<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cardapio_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cardapio_id')->constrained('cardapios')->cascadeOnDelete();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->unsignedSmallInteger('serve_pessoas')->default(1);
            $table->decimal('valor', 10, 2);
            $table->string('imagem_url', 2048)->nullable();
            $table->boolean('visivel')->default(true);
            $table->unsignedInteger('ordem')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cardapio_itens');
    }
};
