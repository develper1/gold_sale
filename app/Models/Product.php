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
        'is_active',
        'is_featured',
        'is_best_seller',
        'image_path',
        'category_id',
        'sub_category_id',
        'status',
        'use_tier_pricing',
        'use_spot_tier_pricing',
        'spot_percentage'
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

    public function spotTierPrices()
    {
        return $this->hasMany(ProductSpotTierPrice::class);
    }

    public function getCurrentPriceAttribute()
    {
        if ($this->use_tier_pricing) {
            $firstTierPrice = $this->tierPrices()
                                   ->join('price_tier_range', 'product_tier_prices.price_tier_range_id', '=', 'price_tier_range.id')
                                   ->orderBy('price_tier_range.tier_start')
                                   ->select('product_tier_prices.price')
                                   ->first();
            return $firstTierPrice ? $firstTierPrice->price : $this->fixed_price;
        }
        if ($this->pricing_type === 'fixed') {
            return $this->fixed_price;
        }
        // For spot pricing, get the current metal price
        $metalPriceService = app(MetalPriceService::class);
        $spotPrice = $metalPriceService->getSpotPrice($this->product_type);
       
        if ($spotPrice === null) {
            return $this->fixed_price;
        }

        // spot percentage
        $spotPrice = $spotPrice * $this->spot_percentage;
        
        // If use_spot_tier_pricing is enabled, override blanket markup with tier
        if ($this->use_spot_tier_pricing) {
            $tier = $this->getSpotTierPriceForQuantity(1); // Default to 1, should be replaced with actual quantity in context
            if ($tier) {
                if ($tier->type === 'percentage') {
                    // Percentage type: replaces blanket markup percentage
                    // Formula: baseSpotPrice * (1 + tierPercentage / 100)
                    $price = $spotPrice * (1 + ($tier->value / 100));
                    return round($price, 2);
                } else { // fixed
                    // Fixed type: overrides spot price completely with fixed amount
                    return round($tier->value, 2);
                }
            }
        }
        // Default: use blanket markup
        $markupPercentage = $this->blanket_markup_percentage;
        if ($markupPercentage) {
            $spotPrice = $spotPrice * (1 + ($markupPercentage / 100));
        }
      
        return round($spotPrice, 2);
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

    /**
     * Get the spot tier price for a given quantity (if use_spot_tier_pricing is enabled)
     */
    public function getSpotTierPriceForQuantity($quantity)
    {
        if (!$this->use_spot_tier_pricing) {
            return null;
        }
        // Join with spot_tier_prices to get the correct tier for the quantity
        $tier = $this->spotTierPrices()
            ->join('spot_tier_prices', 'product_spot_tier_prices.spot_tier_price_id', '=', 'spot_tier_prices.id')
            ->where('spot_tier_prices.tier_start', '<=', $quantity)
            ->where(function($query) use ($quantity) {
                $query->where('spot_tier_prices.tier_end', '>=', $quantity)
                      ->orWhereNull('spot_tier_prices.tier_end');
            })
            ->orderBy('spot_tier_prices.tier_start', 'desc')
            ->select('product_spot_tier_prices.type', 'product_spot_tier_prices.value')
            ->first();
        return $tier;
    }
}