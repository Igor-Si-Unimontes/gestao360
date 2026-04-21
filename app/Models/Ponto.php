<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ponto extends Model
{
    protected $fillable = [
        'usuario_id',
        'entrada_em',
        'saida_em',
    ];

    protected $casts = [
        'entrada_em' => 'datetime',
        'saida_em'   => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function emAberto(): bool
    {
        return $this->saida_em === null;
    }

    public function horasTrabalhadas(): string
    {
        $fim = $this->saida_em ?? now();
        $minutos = max(0, $this->entrada_em->diffInMinutes($fim));

        $horas = intdiv($minutos, 60);
        $resto = $minutos % 60;

        return sprintf('%02d:%02d', $horas, $resto);
    }
}
