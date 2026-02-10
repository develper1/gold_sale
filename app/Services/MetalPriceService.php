<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\MetalPrice;
use Illuminate\Support\Carbon;

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
        $metal = strtolower($productType);

        $record = MetalPrice::where('metal', $metal)
            ->orderByDesc('fetched_at')
            ->first();

        if (! $record) {
            return null;
        }

        return (float) $record->price;

    }

    /**
     * Fetch latest prices for supported metals from the external API
     * and store them in the metal_prices table.
     *
     * This method is intended to be called by a cron-triggered route/command.
     * It does NOT change how getSpotPrice() works for now.
     */
    public function refreshAll(): void
    {
        // Map product types to currency codes
        $currencyMap = [
            'gold'      => 'XAU-ASK',
            'silver'    => 'XAG-ASK',
            'platinum'  => 'XPT-ASK',
            'palladium' => 'XPD-ASK',
        ];

        $currencies = implode(',', array_values($currencyMap));

        $response = Http::get($this->baseUrl, [
            'api_key'    => $this->apiKey,
            'base'       => 'USD',
            'currencies' => $currencies,
        ]);

        if (! $response->successful()) {
            return;
        }

        $data  = $response->json();
        $rates = $data['rates'] ?? [];
        $now   = Carbon::now();

        foreach ($currencyMap as $metal => $code) {
            $rateKey = 'USD' . $code;

            if (! isset($rates[$rateKey])) {
                continue;
            }

            $price = (float) $rates[$rateKey];

            // Look up the previous record for this metal/code to compute change & percent
            $previous = MetalPrice::where('code', $code)
                ->orderByDesc('fetched_at')
                ->first();

            $previousPrice = $previous ? (float) $previous->price : null;

            if ($previousPrice !== null && $previousPrice != 0.0) {
                $change  = $price - $previousPrice;
                $percent = ($change / $previousPrice) * 100;
            } else {
                $change  = 0.0;
                $percent = 0.0;
            }

            MetalPrice::create([
                'metal'      => $metal,
                'code'       => $code,
                'price'      => $price,
                'change'     => $change,
                'percent'    => $percent,
                'fetched_at' => $now,
            ]);
        }
    }
} 