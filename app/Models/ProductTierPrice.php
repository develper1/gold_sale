<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTierPrice extends Model
{
    protected $fillable = [
        'product_id',
        'price_tier_range_id',
        'price'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function priceTierRange()
    {
        return $this->belongsTo(PriceTierRange::class);
    }
} 