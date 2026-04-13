<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    protected $fillable = [
        'tipo',
        'status',
        'valor_total',
        'forma_pagamento',
        'usuario_id',
        'caixa_id',
        'endereco',
        'bairro_id',
        'taxa_entrega',
    ];

    public function bairro()
    {
        return $this->belongsTo(Bairros::class, 'bairro_id');
    }

    public function itens()
    {
        return $this->hasMany(VendaItem::class);
    }
}
