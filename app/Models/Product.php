<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'code',
        'category_id',
        'supplier_id',
        'returnable_product',
        'description',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function getTotalQuantityAttribute() //  * Estoque total (somando todos os lotes).
    {
        return $this->batches()->sum('quantity');
    }
    public function getLastSalePriceAttribute()
    {
        return $this->batches()->latest()->first()?->sale_price;
    }

}
