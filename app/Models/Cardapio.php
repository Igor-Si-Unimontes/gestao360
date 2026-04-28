<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cardapio extends Model
{
    protected $fillable = [
        'nome',
        'descricao',
    ];

    public static function unico(): self
    {
        $c = static::query()->first();
        if ($c) {
            return $c;
        }

        return static::create([
            'nome' => 'Cardápio',
            'descricao' => 'Cardápio visual do estabelecimento.',
        ]);
    }

    public function itens(): HasMany
    {
        return $this->hasMany(CardapioItem::class, 'cardapio_id')
            ->orderByRaw("CASE categoria WHEN 'COMIDA' THEN 1 WHEN 'BEBIDA' THEN 2 WHEN 'SOBREMESA' THEN 3 ELSE 4 END")
            ->orderBy('ordem')
            ->orderBy('id');
    }

    public function itensVisiveis(): HasMany
    {
        return $this->itens()->where('visivel', true);
    }
}
