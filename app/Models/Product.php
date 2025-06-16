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
        'status',
        'use_tier_pricing'
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

    public function tierPrices()
    {
        return $this->hasMany(ProductTierPrice::class);
    }

    public function getCurrentPriceAttribute()
    {
  
        if ($this->use_tier_pricing) {
            // Debugging: Check if use_tier_pricing is true
            // dd('use_tier_pricing is true', $this->id, $this->use_tier_pricing, $this->tierPrices()->get()->toArray(), $this->fixed_price);

            $firstTierPrice = $this->tierPrices()
                                   ->join('price_tier_range', 'product_tier_prices.price_tier_range_id', '=', 'price_tier_range.id')
                                   ->orderBy('price_tier_range.tier_start')
                                   ->select('product_tier_prices.price')
                                   ->first();

            // Debugging: Check the result of the tier price query
            // dd('firstTierPrice query result', $firstTierPrice ? $firstTierPrice->toArray() : null);

            return $firstTierPrice ? $firstTierPrice->price : $this->fixed_price;
        }

        // Debugging: Check if use_tier_pricing is false
        // dd('use_tier_pricing is false', $this->id, $this->use_tier_pricing, $this->fixed_price);

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
        // if ($this->pricing_type === 'fixed') {
        //     return '$' . number_format($this->fixed_price, 2);
        // }

        return '$' . number_format($this->current_price, 2);
    }

    // Add a new method to get tier price based on quantity
    public function getTierPriceForQuantity($quantity)
    {
        if (!$this->use_tier_pricing) {
            return $this->current_price;
        }

        // 1. Try to find a specific tier range (with tier_end defined)
        $tierPrice = $this->tierPrices()
            ->join('price_tier_range', 'product_tier_prices.price_tier_range_id', '=', 'price_tier_range.id')
            ->where('price_tier_range.tier_start', '<=', $quantity)
            ->whereNotNull('price_tier_range.tier_end')
            ->where('price_tier_range.tier_end', '>=', $quantity)
            ->select('product_tier_prices.price')
            ->first();

        // 2. If no specific tier found, try to find an open-ended tier (tier_end is null)
        if (!$tierPrice) {
            $tierPrice = $this->tierPrices()
                ->join('price_tier_range', 'product_tier_prices.price_tier_range_id', '=', 'price_tier_range.id')
                ->where('price_tier_range.tier_start', '<=', $quantity)
                ->whereNull('price_tier_range.tier_end')
                ->select('product_tier_prices.price')
                ->first();
        }

        return $tierPrice ? $tierPrice->price : $this->current_price;
    }
}