<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $fillable = [
        'product_id',
        'quantity',
        'cost_price',
        'sale_price',
        'expiration_date',
        'batch_code',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
