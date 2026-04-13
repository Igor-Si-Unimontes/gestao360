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
            ['nome' => 'Fazendinha', 'taxa' => 3.00],
            ['nome' => 'São Geraldo', 'taxa' => 3.00],
            ['nome' => 'Bom Jesus', 'taxa' => 4.00],
            ['nome' => 'Florestal', 'taxa' => 4.00],
            ['nome' => 'Colinas', 'taxa' => 5.00],
        ];

        foreach ($bairros as $bairro) {
            Bairros::create($bairro);
        }
    }
}