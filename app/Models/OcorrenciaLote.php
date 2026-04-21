<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OcorrenciaLote extends Model
{
    protected $table = 'ocorrencia_lotes';

    protected $fillable = [
        'ocorrencia_id',
        'batch_id',
        'quantidade',
    ];

    protected $casts = [
        'quantidade' => 'decimal:2',
    ];

    public function ocorrencia()
    {
        return $this->belongsTo(Ocorrencia::class, 'ocorrencia_id');
    }

    public function lote()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }
}
