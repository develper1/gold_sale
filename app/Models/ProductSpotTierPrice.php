<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSpotTierPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'spot_tier_price_id',
        'type',
        'value',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function spotTierPrice()
    {
        return $this->belongsTo(SpotTierPrice::class);
    }
} 