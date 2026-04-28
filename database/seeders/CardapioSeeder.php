<?php

namespace Database\Seeders;

use App\Models\Cardapio;
use App\Models\CardapioItem;
use Illuminate\Database\Seeder;

class CardapioSeeder extends Seeder
{
    public function run(): void
    {
        if (CardapioItem::query()->exists()) {
            return;
        }

        $cardapio = Cardapio::unico();

        $itens = [
            [
                'categoria' => CardapioItem::CAT_COMIDA,
                'nome' => 'Prato executivo do dia',
                'descricao' => 'Arroz, feijão, salada, farofa e proteína do dia (ilustrativo).',
                'serve_pessoas' => 1,
                'valor' => 28.90,
                'imagem_url' => null,
                'visivel' => true,
                'ordem' => 1,
            ],
            [
                'categoria' => CardapioItem::CAT_BEBIDA,
                'nome' => 'Suco natural (500 ml)',
                'descricao' => 'Sabores da estação (ilustrativo).',
                'serve_pessoas' => 1,
                'valor' => 9.50,
                'imagem_url' => null,
                'visivel' => true,
                'ordem' => 2,
            ],
            [
                'categoria' => CardapioItem::CAT_SOBREMESA,
                'nome' => 'Sobremesa da casa',
                'descricao' => 'Opção do confeiteiro (ilustrativo).',
                'serve_pessoas' => 1,
                'valor' => 16.50,
                'imagem_url' => null,
                'visivel' => false,
                'ordem' => 3,
            ],
        ];

        foreach ($itens as $row) {
            CardapioItem::create(array_merge($row, ['cardapio_id' => $cardapio->id]));
        }
    }
}
