<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StateFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'fee_type',
        'amount'
    ];

    /**
     * Calculate the state fee amount based on subtotal
     */
    public function calculateFee($subtotal)
    {
        if ($this->fee_type === 'percentage') {
            $fee = ($subtotal * $this->amount) / 100;
            return round($fee, 2);
        }
        
        return round($this->amount, 2);
    }
} 