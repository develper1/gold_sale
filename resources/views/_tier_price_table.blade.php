
    @if($product->use_tier_pricing && $product->tierPrices->isNotEmpty())
        <table class="table table-bordered table-striped" style="margin-top: 12px;">
            <thead>
                <tr>
                    <th>Qty</th>
                    <th>Wire/Check</th>
                    <th>CC/Paypal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($product->tierPrices->sortBy(function($tierPrice) { return $tierPrice->priceTierRange->tier_start; }) as $productTierPrice)
                    @php
                        $qtyLabel = $productTierPrice->priceTierRange->tier_start;
                        $qtyLabel .= $productTierPrice->priceTierRange->tier_end ? ' - ' . $productTierPrice->priceTierRange->tier_end : '+';
                        $wirePrice = (float) $productTierPrice->price;
                        $ccPercent = isset($credit_card_percentage) ? (float) $credit_card_percentage : 0;
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
    @else
        <p>Tier pricing is not enabled or no tier ranges are defined for this product.</p>
    @endif
