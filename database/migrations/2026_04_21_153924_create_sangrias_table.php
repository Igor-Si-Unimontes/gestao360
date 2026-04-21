<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sangrias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caixa_id')->constrained('caixas')->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->date('data_retirada');
            $table->enum('categoria', [
                'DESPESAS',
                'SALARIO',
                'COMPRAS',
                'TROCO',
                'OUTROS',
            ]);
            $table->decimal('valor', 10, 2);
            $table->string('observacao')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sangrias');
    }
};
