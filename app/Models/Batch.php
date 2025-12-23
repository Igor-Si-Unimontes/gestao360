<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\BatchesObserver;

#[ObservedBy(BatchesObserver::class)]
class Batch extends Model
{
    protected $fillable = [
        'product_id',
        'quantity',
        'cost_price',
        'sale_price',
        'expiration_date',
        'batch_code',
        'created_by',
        'updated_by',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
