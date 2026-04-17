<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendaItem extends Model
{
    protected $table = 'venda_itens';

    protected $fillable = [
        'venda_id',
        'produto_id',
        'quantidade',
        'valor_unitario',
        'valor_total',
        'status',
    ];

    public function venda()
    {
        return $this->belongsTo(Venda::class);
    }

    public function produto()
    {
        return $this->belongsTo(Product::class, 'produto_id');
    }
}
