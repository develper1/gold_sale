<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'discount',
        'discount_type',
        // 'order_total',
        // 'product_id',
        'valid_from',
        'valid_to',
        'free_shipping',
        'free_service_fee',
        'is_active',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
