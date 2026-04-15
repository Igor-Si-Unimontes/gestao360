<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    protected $fillable = ['numero', 'capacidade', 'status'];

    public function vendas()
    {
        return $this->hasMany(Venda::class);
    }

    public function vendaAberta()
    {
        return $this->hasOne(Venda::class)->where('status', 'ABERTA');
    }

    public function isLivre(): bool
    {
        return $this->status === 'livre';
    }
}
