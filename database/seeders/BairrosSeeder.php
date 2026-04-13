<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bairro;
use App\Models\Bairros;

class BairrosSeeder extends Seeder
{
    public function run(): void
    {
        $bairros = [

            ['nome' => 'Centro', 'taxa' => 2.00],
            ['nome' => 'Todos os Santos', 'taxa' => 3.00],
            ['nome' => 'Funcionários', 'taxa' => 3.00],

            ['nome' => 'Major Prates', 'taxa' => 4.00],
            ['nome' => 'São José', 'taxa' => 4.00],
            ['nome' => 'Santa Rita', 'taxa' => 4.00],
            ['nome' => 'Delfino Magalhães', 'taxa' => 4.00],
            ['nome' => 'Canelas', 'taxa' => 4.00],

            ['nome' => 'Independência', 'taxa' => 5.00],
            ['nome' => 'Maracanã', 'taxa' => 5.00],
            ['nome' => 'Renascença', 'taxa' => 5.00],
            ['nome' => 'Jardim Palmeiras', 'taxa' => 5.00],
            ['nome' => 'Jardim Primavera', 'taxa' => 5.00],

            ['nome' => 'Augusta Mota', 'taxa' => 6.00],
            ['nome' => 'Santos Reis', 'taxa' => 6.00],
            ['nome' => 'Vila Oliveira', 'taxa' => 6.00],
            ['nome' => 'Planalto', 'taxa' => 6.00],
            ['nome' => 'Esplanada', 'taxa' => 6.00],

            ['nome' => 'Ibituruna', 'taxa' => 7.00],
            ['nome' => 'Monte Carmelo', 'taxa' => 7.00],
            ['nome' => 'Cidade Industrial', 'taxa' => 7.00],
            ['nome' => 'Jaraguá', 'taxa' => 7.00],
            ['nome' => 'Vila Atlântida', 'taxa' => 7.00],

            ['nome' => 'Nossa Senhora Aparecida', 'taxa' => 8.00],
            ['nome' => 'São Geraldo', 'taxa' => 8.00],
            ['nome' => 'São Judas', 'taxa' => 8.00],
            ['nome' => 'Santa Lúcia', 'taxa' => 8.00],
            ['nome' => 'Roxo Verde', 'taxa' => 8.00],

            ['nome' => 'Vila Regina', 'taxa' => 9.00],
            ['nome' => 'Morada do Parque', 'taxa' => 9.00],
        ];

        foreach ($bairros as $bairro) {
            Bairros::create($bairro);
        }
    }
}