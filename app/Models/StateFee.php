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
        'amount'
    ];
} 