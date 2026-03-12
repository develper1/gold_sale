<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderRefund extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'amount',
        'refund_type',
        'gateway_refund_id',
        'reason',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
