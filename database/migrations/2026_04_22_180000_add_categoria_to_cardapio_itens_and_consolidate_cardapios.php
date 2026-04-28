<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('cardapio_itens', 'categoria')) {
            Schema::table('cardapio_itens', function (Blueprint $table) {
                $table->string('categoria', 20)->default('COMIDA')->after('cardapio_id');
            });
        }

        $firstId = DB::table('cardapios')->orderBy('id')->value('id');

        if (! $firstId) {
            $firstId = DB::table('cardapios')->insertGetId([
                'nome' => 'Cardápio',
                'descricao' => 'Cardápio visual do estabelecimento.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            DB::table('cardapio_itens')->where('cardapio_id', '!=', $firstId)->update(['cardapio_id' => $firstId]);
            DB::table('cardapios')->where('id', '!=', $firstId)->delete();
        }

        DB::table('cardapios')->where('id', $firstId)->update([
            'nome' => 'Cardápio',
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::table('cardapio_itens', function (Blueprint $table) {
            $table->dropColumn('categoria');
        });
    }
};
