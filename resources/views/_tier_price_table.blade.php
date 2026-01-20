@php
    // Only apply credit card fee for gold, silver, and platinum products
    $isGoldSilverOrPlatinum = in_array($product->product_type, ['gold', 'silver', 'platinum']);
    $ccPercent = $isGoldSilverOrPlatinum && isset($credit_card_percentage) ? (float) $credit_card_percentage : 0;
    $spotPrice = null;
    if ($product->pricing_type === 'spot') {
        $metalPriceService = app(\App\Services\MetalPriceService::class);
        $rawSpotPrice = $metalPriceService->getSpotPrice($product->product_type);
        if ($rawSpotPrice !== null) {
            $spotPrice = $rawSpotPrice * ($product->spot_percentage ?? 1);
        }
    }
@endphp

@if($product->use_tier_pricing && $product->tierPrices->isNotEmpty())
    <table class="table table-bordered table-striped" style="margin-top: 12px;">
        <thead>
            <tr>
                <th>Qty</th>
                <th>
                    @if($isGoldSilverOrPlatinum)
                        Wire/Check
                    @else
                        Price
                    @endif
                </th>
                @if($isGoldSilverOrPlatinum)
                    <th>CC/Paypal</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($product->tierPrices->sortBy(function($tierPrice) { return $tierPrice->priceTierRange->tier_start; }) as $productTierPrice)
                @php
                    $qtyLabel = $productTierPrice->priceTierRange->tier_start;
                    $qtyLabel .= $productTierPrice->priceTierRange->tier_end ? ' - ' . $productTierPrice->priceTierRange->tier_end : '+';
                    $wirePrice = (float) $productTierPrice->price;
                    // Round wire price to 2 decimal places for consistency
                    $wirePrice = round($wirePrice, 2);
                    $ccPrice = round($wirePrice * (1 + ($ccPercent / 100)), 2);
                @endphp
                <tr>
                    <td>{{ $qtyLabel }}</td>
                    <td>${{ number_format($wirePrice, 2) }}</td>
                    @if($isGoldSilverOrPlatinum)
                    <td>${{ number_format($ccPrice, 2) }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

@if($product->use_spot_tier_pricing && $product->spotTierPrices->isNotEmpty() && $spotPrice !== null)
    <table class="table table-bordered table-striped" style="margin-top: {{ $product->use_tier_pricing && $product->tierPrices->isNotEmpty() ? '20px' : '12px' }};">
        <thead>
            <tr>
                <th>Qty</th>
                <th>Wire/Check</th>
                <th>CC/Paypal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($product->spotTierPrices->sortBy(function($spotTierPrice) { return $spotTierPrice->spotTierPrice->tier_start; }) as $productSpotTierPrice)
                @php
                    $spotTier = $productSpotTierPrice->spotTierPrice;
                    $qtyLabel = $spotTier->tier_start;
                    $qtyLabel .= $spotTier->tier_end ? ' - ' . $spotTier->tier_end : '+';
                    
                    // Get type and value from product override or from spot tier
                    $tierType = $productSpotTierPrice->type ?? $spotTier->type ?? 'percentage';
                    $tierValue = (float) ($productSpotTierPrice->value ?? $spotTier->value ?? 0);
                    
                    // Calculate wire price based on type
                    // Note: $spotPrice is already baseSpotPrice (rawSpotPrice * spot_percentage)
                    if ($tierType === 'percentage') {
                        // Percentage type: replaces blanket markup percentage
                        // Formula: baseSpotPrice * (1 + tierPercentage / 100)
                        $wirePrice = $spotPrice * (1 + ($tierValue / 100));
                    } else { // fixed
                        // Fixed type: overrides spot price completely with fixed amount
                        $wirePrice = $tierValue;
                    }
                    $wirePrice = round($wirePrice, 2);
                    
                    // Calculate CC price
                    $ccPrice = round($wirePrice * (1 + ($ccPercent / 100)), 2);
                @endphp
                <tr>
                    <td>{{ $qtyLabel }}</td>
                    <td>${{ number_format($wirePrice, 2) }}</td>
                    <td>${{ number_format($ccPrice, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

@if(!$product->use_tier_pricing && !$product->use_spot_tier_pricing)
    <p>Tier pricing is not enabled or no tier ranges are defined for this product.</p>
@endif
