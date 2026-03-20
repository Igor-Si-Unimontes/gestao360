<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fiscal extends Model
{
    protected $fillable = [
        'product_id',
        'cProd',
        'cEAN',
        'xProd',
        'NCM',
        'CEST',
        'CFOP',
        'cEANTrib',
        'CST',
        'pST',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
