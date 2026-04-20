<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('caixas', function (Blueprint $table) {
            $table->json('abertura_contagem')->nullable()->after('valor_abertura');
            $table->json('fechamento_contagem')->nullable()->after('valor_fechamento');
            $table->timestamp('fechado_em')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('caixas', function (Blueprint $table) {
            $table->dropColumn(['abertura_contagem', 'fechamento_contagem', 'fechado_em']);
        });
    }
};
