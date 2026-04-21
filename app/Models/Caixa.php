<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Caixa extends Model
{
    protected $fillable = [
        'valor_abertura',
        'abertura_contagem',
        'valor_fechamento',
        'fechamento_contagem',
        'status',
        'usuario_id',
        'fechado_em',
    ];

    protected $casts = [
        'abertura_contagem'   => 'array',
        'fechamento_contagem' => 'array',
        'fechado_em'          => 'datetime',
        'valor_abertura'      => 'decimal:2',
        'valor_fechamento'    => 'decimal:2',
    ];

    public static array $denominacoes = [
        'nota_100'  => 100.00,
        'nota_50'   => 50.00,
        'nota_20'   => 20.00,
        'nota_10'   => 10.00,
        'nota_5'    => 5.00,
        'nota_2'    => 2.00,
        'moeda_100' => 1.00,
        'moeda_050' => 0.50,
        'moeda_025' => 0.25,
        'moeda_010' => 0.10,
        'moeda_005' => 0.05,
    ];

    public static function calcularTotal(array $contagem): float
    {
        $total = 0;
        foreach (self::$denominacoes as $key => $valor) {
            $total += ($contagem[$key] ?? 0) * $valor;
        }
        return round($total, 2);
    }


    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function vendas()
    {
        return $this->hasMany(Venda::class, 'caixa_id');
    }

    public function sangrias()
    {
        return $this->hasMany(Sangria::class, 'caixa_id');
    }

    public function totalSangrias(): float
    {
        return (float) $this->sangrias()->sum('valor');
    }

    public static function aberto(): ?self
    {
        return self::where('status', 'ABERTO')->latest()->first();
    }

    public function totalDinheiro(): float
    {
        return (float) $this->vendas()
            ->where('status', 'FINALIZADA')
            ->where('forma_pagamento', 'DINHEIRO')
            ->sum('valor_total');
    }

    public function totalCartao(): float
    {
        return (float) $this->vendas()
            ->where('status', 'FINALIZADA')
            ->whereIn('forma_pagamento', ['CARTAO_DEBITO', 'CARTAO_CREDITO'])
            ->sum('valor_total');
    }

    public function totalPix(): float
    {
        return (float) $this->vendas()
            ->where('status', 'FINALIZADA')
            ->where('forma_pagamento', 'PIX')
            ->sum('valor_total');
    }

    public function valorEsperadoFechamento(): float
    {
        return round((float) $this->valor_abertura + $this->totalDinheiro() - $this->totalSangrias(), 2);
    }

    public function tempoAberto(): string
    {
        return $this->created_at->diffForHumans();
    }
}
