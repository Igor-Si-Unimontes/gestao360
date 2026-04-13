<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendas', function (Blueprint $table) {
            $table->string('endereco')->nullable()->after('caixa_id');
            $table->unsignedBigInteger('bairro_id')->nullable()->after('endereco');
            $table->decimal('taxa_entrega', 8, 2)->default(0)->after('bairro_id');

            $table->foreign('bairro_id')->references('id')->on('bairros')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('vendas', function (Blueprint $table) {
            $table->dropForeign(['bairro_id']);
            $table->dropColumn(['endereco', 'bairro_id', 'taxa_entrega']);
        });
    }
};
