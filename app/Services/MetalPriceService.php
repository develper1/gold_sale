<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class MetalPriceService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = 'fb90526111cd959a88ed7d761670db19';
        $this->baseUrl = 'https://api.metalpriceapi.com/v1/latest';
    }

    public function getSpotPrice($productType)
    {
        // Cache the price for 5 minutes to avoid too many API calls
        return Cache::remember("metal_price_{$productType}", 300, function () use ($productType) {
            $currency = strtolower($productType) === 'gold' ? 'XAU' : 'XAG';
            
            $response = Http::get($this->baseUrl, [
                'api_key' => $this->apiKey,
                'base' => 'USD',
                'currencies' => $currency
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['rates']['USD'.$currency] ?? null;
            }

            return null;
        });
    }
} 