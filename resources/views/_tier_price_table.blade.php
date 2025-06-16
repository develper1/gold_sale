
    @if($product->use_tier_pricing && $product->tierPrices->isNotEmpty())
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Quantity Range</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($product->tierPrices->sortBy(function($tierPrice) { return $tierPrice->priceTierRange->tier_start; }) as $productTierPrice)
                    <tr>
                        <td>
                            {{ $productTierPrice->priceTierRange->tier_start }}
                            @if($productTierPrice->priceTierRange->tier_end)
                                - {{ $productTierPrice->priceTierRange->tier_end }}
                            @else
                                +
                            @endif
                        </td>
                        <td>${{ number_format($productTierPrice->price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Tier pricing is not enabled or no tier ranges are defined for this product.</p>
    @endif
