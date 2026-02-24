<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'sort_order'];

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class)->orderBy('sort_order')->orderBy('name');
    }

    public function products()
    {
        return $this->hasMany(\App\Models\Product::class);
    }
} 