<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sangria extends Model
{
    protected $fillable = [
        'caixa_id',
        'usuario_id',
        'data_retirada',
        'categoria',
        'valor',
        'observacao',
    ];

    protected $casts = [
        'data_retirada' => 'date',
        'valor'         => 'decimal:2',
    ];

    public static array $categorias = [
        'DESPESAS' => 'Despesas',
        'SALARIO'  => 'Salário',
        'COMPRAS'  => 'Compras',
        'TROCO'    => 'Troco',
        'OUTROS'   => 'Outros',
    ];

    public function caixa()
    {
        return $this->belongsTo(Caixa::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
