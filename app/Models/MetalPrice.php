<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetalPrice extends Model
{
    protected $table = 'metal_prices';

    protected $fillable = [
        'metal',
        'code',
        'price',
        'change',
        'percent',
        'fetched_at',
    ];

    protected $casts = [
        'price' => 'decimal:6',
        'change' => 'decimal:6',
        'percent' => 'decimal:4',
        'fetched_at' => 'datetime',
    ];
}
