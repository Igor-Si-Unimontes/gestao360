<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ocorrencia extends Model
{
    protected $fillable = [
        'produto_id',
        'usuario_id',
        'quantidade',
        'motivo',
    ];

    protected $casts = [
        'quantidade' => 'decimal:2',
    ];

    public function produto()
    {
        return $this->belongsTo(Product::class, 'produto_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function lotes()
    {
        return $this->hasMany(OcorrenciaLote::class, 'ocorrencia_id');
    }
}
