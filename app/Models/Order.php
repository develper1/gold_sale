<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'billing_first_name',
        'billing_last_name',
        'billing_email',
        'billing_phone',
        'billing_address_1',
        'billing_address_2',
        'billing_city',
        'billing_state',
        'billing_postcode',
        'billing_country',
        'shipping_first_name',
        'shipping_last_name',
        'shipping_company',
        'shipping_address_1',
        'shipping_address_2',
        'shipping_city',
        'shipping_state',
        'shipping_postcode',
        'shipping_country',
        'order_comments',
        'subtotal',
        'shipping_fee',
        'state_fee',
        'service_fee',
        'total',
        'payment_method',
        'status',
        'order_uid',
        'transaction_id',
        'credit_card_fee',
        'credit_card_percentage',
        'admin_notes',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function generateOrderUid()
    {
        do {
            $uid = strtoupper(Str::random(8));
        } while (self::where('order_uid', $uid)->exists());
        return $uid;
    }
} 