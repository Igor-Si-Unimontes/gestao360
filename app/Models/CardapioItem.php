<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CardapioItem extends Model
{
    public const CAT_COMIDA = 'COMIDA';

    public const CAT_BEBIDA = 'BEBIDA';

    public const CAT_SOBREMESA = 'SOBREMESA';

    /**
     * @var array<string, string>
     */
    public static array $categorias = [
        self::CAT_COMIDA => 'Comidas',
        self::CAT_BEBIDA => 'Bebidas',
        self::CAT_SOBREMESA => 'Sobremesas',
    ];

    protected $table = 'cardapio_itens';

    protected $fillable = [
        'cardapio_id',
        'categoria',
        'nome',
        'descricao',
        'serve_pessoas',
        'valor',
        'imagem_url',
        'visivel',
        'ordem',
    ];

    protected function casts(): array
    {
        return [
            'serve_pessoas' => 'integer',
            'valor' => 'decimal:2',
            'visivel' => 'boolean',
            'ordem' => 'integer',
        ];
    }

    public function cardapio(): BelongsTo
    {
        return $this->belongsTo(Cardapio::class, 'cardapio_id');
    }

    public function labelCategoria(): string
    {
        return self::$categorias[$this->categoria] ?? $this->categoria;
    }

    public function urlImagemExibicao(): string
    {
        if ($this->imagem_url) {
            return $this->imagem_url;
        }

        $seed = $this->id
            ? 'c'.$this->cardapio_id.'i'.$this->id
            : 'c'.$this->cardapio_id.'n'.substr(md5((string) $this->nome), 0, 12);

        return 'https://picsum.photos/seed/'.$seed.'/640/400';
    }
}
