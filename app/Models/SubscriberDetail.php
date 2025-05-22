<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriberDetail extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'city',
        'email',
        'investment_type',
        'investment_criteria',
        'contact_preference',
        'mobile_number'
    ];

    protected $casts = [
        'investment_type' => 'array'
    ];
} 