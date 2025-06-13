<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\MetalPriceService;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded=[];

    protected $fillable = [
        'name',
        'slug',
        'description',
        'product_type',
        'pricing_type',
        'fixed_price',
        'blanket_markup_percentage',
        'use_override_markup',
        'override_markup_percentage',
        'inventory_type',
        'quantity_available',
        'low_inventory_threshold',
        'image_path',
        'category_id',
        'subcategory_id',
        'status'
    ];

    protected $appends = ['current_price', 'formatted_price'];

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getCurrentPriceAttribute()
    {
        if ($this->pricing_type === 'fixed') {
            return $this->fixed_price;
        }

        // For spot pricing, we need to get the current metal price
        $metalPriceService = app(MetalPriceService::class);
        $spotPrice = $metalPriceService->getSpotPrice($this->product_type);

        if ($spotPrice === null) {
            // If we can't get the spot price, fallback to fixed price
            return $this->fixed_price;
        }

        // Apply markup if available
        $markupPercentage = $this->use_override_markup ? 
            $this->override_markup_percentage : 
            $this->blanket_markup_percentage;
            
        if ($markupPercentage) {
            $spotPrice = $spotPrice * (1 + ($markupPercentage / 100));
        }

        return $spotPrice;
    }

    public function getFormattedPriceAttribute()
    {
        if ($this->pricing_type === 'fixed') {
            return '$' . number_format($this->fixed_price, 2);
        }

        return '$' . number_format($this->current_price, 2);
    }
}