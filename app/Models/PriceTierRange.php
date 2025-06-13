<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceTierRange extends Model
{
    use HasFactory;

    protected $fillable = ['tier_start', 'tier_end', 'tier_price'];

} 