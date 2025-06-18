<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpotTierPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'tier_start',
        'tier_end',
        'type', // 'percentage' or 'fixed'
        'value',
    ];

    protected $casts = [
        'tier_start' => 'integer',
        'tier_end' => 'integer',
        'value' => 'decimal:2',
    ];

    protected $table = 'spot_tier_prices';

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
} 