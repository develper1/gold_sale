<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceTierRange extends Model
{
    use HasFactory;

    protected $fillable = [
        'tier_start',
        'tier_end',
        'tier_price'
    ];

    protected $casts = [
        'tier_start' => 'integer',
        'tier_end' => 'integer',
        'tier_price' => 'decimal:2'
    ];

    protected $table = 'price_tier_range';
} 