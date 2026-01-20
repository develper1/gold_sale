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
        $this->apiKey = env('METALPRICE_API_KEY');
        $this->baseUrl = 'https://api.metalpriceapi.com/v1/latest';
    }

    public function getSpotPrice($productType)
    {
        $currencyMap = [
            'gold' => 'XAU',
            'silver' => 'XAG',
            'platinum' => 'XPT'
        ];
        
        $productTypeLower = strtolower($productType);
        $currency = $currencyMap[$productTypeLower] ?? null;
        
        if (!$currency) {
            return null;
        }
        
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
        
    }
} 