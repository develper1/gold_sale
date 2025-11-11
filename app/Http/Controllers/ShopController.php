<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::with('subCategories')->get();
        
        // Get product counts for all subcategories
        $productCounts = Product::where('is_active', true)
            ->select('sub_category_id', DB::raw('count(*) as total'))
            ->groupBy('sub_category_id')
            ->pluck('total', 'sub_category_id')
            ->toArray();
            
        // Add counts to subcategories
        foreach ($categories as $category) {
            foreach ($category->subCategories as $subCategory) {
                $subCategory->products_count = $productCounts[$subCategory->id] ?? 0;
            }
        }
        
        $sort = $request->input('sort', 'default');
        $productsQuery = Product::where('is_active', true)->with(['images', 'subCategory', 'tierPrices.priceTierRange', 'spotTierPrices.spotTierPrice']);
        if ($sort === 'latest') {
            $productsQuery->orderBy('id', 'desc');
        } else {
            $productsQuery->orderBy('id', 'asc');
        }
        $products = $productsQuery->get();
        if ($sort === 'price_asc') {
            $products = $products->sortBy(function($product) { return $product->current_price; })->values();
        } elseif ($sort === 'price_desc') {
            $products = $products->sortByDesc(function($product) { return $product->current_price; })->values();
        }
        $setting = \App\Models\Setting::first();
        $credit_card_percentage = $setting ? $setting->credit_card_percentage : 0;
        return view('thumbs', compact('categories', 'products', 'credit_card_percentage'));
    }

    public function subcategory(Request $request, $slug)
    {
        $categories = Category::with('subCategories')->get();
        
        // Get product counts for all subcategories
        $productCounts = Product::where('is_active', true)
            ->select('sub_category_id', DB::raw('count(*) as total'))
            ->groupBy('sub_category_id')
            ->pluck('total', 'sub_category_id')
            ->toArray();
            
        // Add counts to subcategories
        foreach ($categories as $category) {
            foreach ($category->subCategories as $subCategory) {
                $subCategory->products_count = $productCounts[$subCategory->id] ?? 0;
            }
        }
        
        $subcategory = SubCategory::where('slug', $slug)->firstOrFail();
        $sort = $request->input('sort', 'default');
        $productsQuery = Product::where('sub_category_id', $subcategory->id)
            ->where('is_active', true)
            ->with(['images', 'tierPrices.priceTierRange', 'spotTierPrices.spotTierPrice']);
        if ($sort === 'latest') {
            $productsQuery->orderBy('id', 'desc');
        } else {
            $productsQuery->orderBy('id', 'asc');
        }
        $products = $productsQuery->get();
        if ($sort === 'price_asc') {
            $products = $products->sortBy(function($product) { return $product->current_price; })->values();
        } elseif ($sort === 'price_desc') {
            $products = $products->sortByDesc(function($product) { return $product->current_price; })->values();
        }
        
        $setting = \App\Models\Setting::first();
        $credit_card_percentage = $setting ? $setting->credit_card_percentage : 0;
        return view('thumbs', compact('categories', 'subcategory', 'products', 'credit_card_percentage'));
    }

    public function category(Request $request, $slug)
    {
        $categories = Category::with('subCategories')->get();
        
        // Get product counts for all subcategories
        $productCounts = Product::where('is_active', true)
            ->select('sub_category_id', DB::raw('count(*) as total'))
            ->groupBy('sub_category_id')
            ->pluck('total', 'sub_category_id')
            ->toArray();
            
        // Add counts to subcategories
        foreach ($categories as $category) {
            foreach ($category->subCategories as $subCategory) {
                $subCategory->products_count = $productCounts[$subCategory->id] ?? 0;
            }
        }
        
        $category = Category::where('slug', $slug)->firstOrFail();
        
        $sort = $request->input('sort', 'default');
        $productsQuery = Product::whereHas('subCategory', function($query) use ($category) {
            $query->where('category_id', $category->id);
        })
        ->where('is_active', true)
        ->with(['images', 'subCategory', 'tierPrices.priceTierRange', 'spotTierPrices.spotTierPrice']);
        if ($sort === 'latest') {
            $productsQuery->orderBy('id', 'desc');
        } else {
            $productsQuery->orderBy('id', 'asc');
        }
        $products = $productsQuery->get();
        if ($sort === 'price_asc' || $sort === 'price_desc') {
            $products = ($sort === 'price_asc')
                ? $products->sortBy(function($product) { return $product->current_price; })->values()
                : $products->sortByDesc(function($product) { return $product->current_price; })->values();
            $products = $products->groupBy('sub_category_id');
        } else {
            $products = $products->groupBy('sub_category_id');
        }
        
        $setting = \App\Models\Setting::first();
        $credit_card_percentage = $setting ? $setting->credit_card_percentage : 0;
        return view('thumbs', compact('categories', 'category', 'products', 'credit_card_percentage'));
    }

    public function product($slug)
    {
        $categories = Category::with('subCategories')->get();
        
        // Get product counts for all subcategories
        $productCounts = Product::where('is_active', true)
            ->select('sub_category_id', DB::raw('count(*) as total'))
            ->groupBy('sub_category_id')
            ->pluck('total', 'sub_category_id')
            ->toArray();
            
        // Add counts to subcategories
        foreach ($categories as $category) {
            foreach ($category->subCategories as $subCategory) {
                $subCategory->products_count = $productCounts[$subCategory->id] ?? 0;
            }
        }
        
        $product = Product::where('slug', $slug)
                        ->where('is_active', true)
                        ->with(['images', 'subCategory', 'tierPrices.priceTierRange', 'spotTierPrices.spotTierPrice'])
                        ->firstOrFail();
        $setting = \App\Models\Setting::first();
        $credit_card_percentage = $setting ? $setting->credit_card_percentage : 0;
        return view('product-detail', compact('categories', 'product', 'credit_card_percentage'));
    }

    /**
     * Calculate product price based on quantity and tier pricing
     */
    private function calculatePriceForQuantity(Product $product, $quantity)
    {
        // Handle regular tier pricing
        if ($product->use_tier_pricing) {
            return $product->getTierPriceForQuantity($quantity);
        }
        
        // Handle spot tier pricing
        if ($product->use_spot_tier_pricing && $product->pricing_type === 'spot') {
            $tier = $product->getSpotTierPriceForQuantity($quantity);
            $metalPriceService = app(\App\Services\MetalPriceService::class);
            $rawSpotPrice = $metalPriceService->getSpotPrice($product->product_type);
            
            if ($rawSpotPrice === null) {
                return $product->fixed_price;
            }
            
            // Apply spot_percentage to get base spot price
            $baseSpotPrice = $rawSpotPrice * ($product->spot_percentage ?? 1);
            
            if ($tier) {
                // Tier pricing overrides blanket markup
                if ($tier->type === 'percentage') {
                    // Percentage type: replaces blanket markup percentage for this quantity
                    // Use the tier percentage instead of blanket markup
                    $price = $baseSpotPrice * (1 + ($tier->value / 100));
                    return round($price, 2);
                } else { // fixed
                    // Fixed type: overrides spot price completely with fixed amount
                    return round($tier->value, 2);
                }
            } else {
                // Fallback to blanket markup if no tier found for this quantity
                $markupPercentage = $product->blanket_markup_percentage;
                $price = $baseSpotPrice;
                if ($markupPercentage) {
                    $price = $baseSpotPrice * (1 + ($markupPercentage / 100));
                }
                return round($price, 2);
            }
        }
        
        // For non-tier pricing, use current price (already rounded in getCurrentPriceAttribute)
        return $product->current_price;
    }

    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);
        
        // Convert productId to integer for consistent cart key storage
        $productId = (int) $productId;
        
        $product = Product::with('images')->findOrFail($productId);
        
        // Check inventory for limited products
        if ($product->inventory_type === 'limited' && $product->quantity_available !== null) {
            $cart = session()->get('cart', []);
            $currentCartQuantity = isset($cart[$productId]) ? $cart[$productId]['quantity'] : 0;
            $requestedQuantity = $currentCartQuantity + $quantity;
            
            if ($requestedQuantity > $product->quantity_available) {
                $message = 'Insufficient stock. Only ' . $product->quantity_available . ' items available.';
                if ($currentCartQuantity > 0) {
                    $message .= ' You already have ' . $currentCartQuantity . ' in your cart.';
                }
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 422);
            }
        }
        
        $cart = session()->get('cart', []);
        
        if(isset($cart[$productId])) {
            // Product already in cart - update quantity and recalculate price
            $newQuantity = $cart[$productId]['quantity'] + $quantity;
            $cart[$productId]['quantity'] = $newQuantity;
            // Recalculate price based on new total quantity (tier pricing)
            $cart[$productId]['price'] = $this->calculatePriceForQuantity($product, $newQuantity);
        } else {
            // New product - calculate price based on quantity
            $price = $this->calculatePriceForQuantity($product, $quantity);
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $price,
                'pricing_type' => $product->pricing_type,
                'quantity' => $quantity,
                'image' => $product->images->first() ? $product->images->first()->image_path : null,
                'slug' => $product->slug,
                'use_tier_pricing' => $product->use_tier_pricing,
                'use_spot_tier_pricing' => $product->use_spot_tier_pricing,
                'inventory_type' => $product->inventory_type,
                'quantity_available' => $product->quantity_available
            ];
        }
        
        session()->put('cart', $cart);
        
        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully',
            'cart_count' => count($cart)
        ]);
    }

    public function viewCart()
    {
        $cart = session()->get('cart', []);
        // Ensure each cart item has up-to-date price and flags on every page load
        $total = 0;
        foreach ($cart as $key => $item) {
            try {
                $product = Product::find($item['id']);
                if ($product) {
                    // Recalculate price based on current product settings and item quantity
                    $quantity = (int) ($item['quantity'] ?? 1);
                    $currentPrice = $this->calculatePriceForQuantity($product, $quantity);
                    // Update cart item with latest price and flags
                    $cart[$key]['price'] = $currentPrice;
                    $cart[$key]['pricing_type'] = $product->pricing_type;
                    $cart[$key]['use_tier_pricing'] = $product->use_tier_pricing;
                    $cart[$key]['use_spot_tier_pricing'] = $product->use_spot_tier_pricing;
                    $cart[$key]['inventory_type'] = $product->inventory_type;
                    $cart[$key]['quantity_available'] = $product->quantity_available;
                }
            } catch (\Throwable $e) {
                // Ignore per-item failures and keep existing values
            }
            $total += ($cart[$key]['price'] ?? 0) * ($cart[$key]['quantity'] ?? 1);
        }
        $total = round($total, 2);
        // Persist any updates back to the session
        session()->put('cart', $cart);
        
        return view('cart', compact('cart', 'total'));
    }

    public function updateCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');
        $cart = session()->get('cart', []);

        // Convert productId to integer to match cart keys
        $productId = (int) $productId;

        if(isset($cart[$productId])) {
            $product = Product::findOrFail($productId);
            
            // Check inventory for limited products
            if ($product->inventory_type === 'limited' && $product->quantity_available !== null) {
                if ($quantity > $product->quantity_available) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Insufficient stock. Only ' . $product->quantity_available . ' items available.'
                    ], 422);
                }
            }
            
            // Calculate price based on quantity using the helper method
            // This handles both regular tier pricing and spot tier pricing correctly
            $price = $this->calculatePriceForQuantity($product, $quantity);
            
            $cart[$productId]['quantity'] = $quantity;
            $cart[$productId]['price'] = $price;
            $cart[$productId]['use_tier_pricing'] = $product->use_tier_pricing;
            $cart[$productId]['use_spot_tier_pricing'] = $product->use_spot_tier_pricing;
            $cart[$productId]['inventory_type'] = $product->inventory_type;
            $cart[$productId]['quantity_available'] = $product->quantity_available;
            session()->put('cart', $cart);
            
            // Calculate new totals
            $total = 0;
            foreach($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }
            $total = round($total, 2);

            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully',
                'cart_count' => count($cart),
                'subtotal' => $total,
                'total' => $total,
                'item_price' => $price
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product not found in cart'
        ], 404);
    }

    public function removeFromCart(Request $request)
    {
        $productId = $request->input('product_id');
        $cart = session()->get('cart', []);

        // Convert productId to integer to match cart keys (cart keys are stored as integers from addToCart)
        $productId = (int) $productId;
        
        // Also check if it exists as a string key (for backward compatibility)
        if(isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
            
            // Calculate new totals
            $total = 0;
            foreach($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }
            $total = round($total, 2);

            return response()->json([
                'success' => true,
                'message' => 'Product removed from cart',
                'cart_count' => count($cart),
                'subtotal' => $total,
                'total' => $total
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product not found in cart'
        ], 404);
    }

    public function getCartCount()
    {
        $cart = session()->get('cart', []);
        return response()->json([
            'count' => count($cart)
        ]);
    }

    public function quickView($id)
    {
        $product = Product::with(['images', 'subCategory', 'tierPrices.priceTierRange', 'spotTierPrices.spotTierPrice'])
                         ->findOrFail($id);
        
        $setting = \App\Models\Setting::first();
        $credit_card_percentage = $setting ? $setting->credit_card_percentage : 0;
        
        // Get current spot price if product uses spot pricing
        $spotPrice = null;
        if ($product->pricing_type === 'spot') {
            $metalPriceService = app(\App\Services\MetalPriceService::class);
            $rawSpotPrice = $metalPriceService->getSpotPrice($product->product_type);
            if ($rawSpotPrice !== null) {
                $spotPrice = $rawSpotPrice * ($product->spot_percentage ?? 1);
            }
        }
        
        return response()->json([
            'success' => true,
            'product' => $product,
            'credit_card_percentage' => $credit_card_percentage,
            'spot_price' => $spotPrice
        ]);
    }

    public function clearCart()
    {
        session()->forget('cart');
        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully'
        ]);
    }

    public function getTierPricesModal(Product $product)
    {
        $product->load('tierPrices.priceTierRange');
        return view('_tier_price_table', compact('product'));
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        $stateFees = \App\Models\StateFee::all(['code', 'amount']);
        $setting = \App\Models\Setting::first();
        $creditCardPercentage = $setting ? $setting->credit_card_percentage : 0;
        return view('checkout', compact('cart', 'total', 'stateFees', 'creditCardPercentage'));
    }

    /**
     * Get shipping fee for a given subtotal (AJAX endpoint)
     */
    public function getShippingFee($subtotal)
    {
        $shippings = \App\Models\Shipping::orderBy('order_amount')->get();
        $fee = 0;
        if ($shippings->count() > 0) {
            // If subtotal is less than the lowest order_amount, use the lowest bracket
            if ($subtotal < $shippings->first()->order_amount) {
                $fee = $shippings->first()->shipping_charges;
            } else {
                foreach ($shippings as $index => $shipping) {
                    $next = $shippings->get($index + 1);
                    if ($next) {
                        if ($subtotal >= $shipping->order_amount && $subtotal < $next->order_amount) {
                            $fee = $shipping->shipping_charges;
                            break;
                        }
                    } else {
                        // Last range: if subtotal >= last order_amount
                        if ($subtotal >= $shipping->order_amount) {
                            $fee = $shipping->shipping_charges;
                            break;
                        }
                    }
                }
            }
        }
        return response()->json(['amount' => $fee]);
    }

    /**
     * Get service fee for a given subtotal (AJAX endpoint)
     */
    public function getServiceFee($subtotal)
    {
        $services = \App\Models\Service::orderBy('order_amount')->get();
        $fee = 0;
        if ($services->count() > 0) {
            // If subtotal is less than the lowest order_amount, use the lowest bracket
            if ($subtotal < $services->first()->order_amount) {
                $fee = $services->first()->services_fee;
            } else {
                foreach ($services as $index => $service) {
                    $next = $services->get($index + 1);
                    if ($next) {
                        if ($subtotal >= $service->order_amount && $subtotal < $next->order_amount) {
                            $fee = $service->services_fee;
                            break;
                        }
                    } else {
                        // Last range: if subtotal >= last order_amount
                        if ($subtotal >= $service->order_amount) {
                            $fee = $service->services_fee;
                            break;
                        }
                    }
                }
            }
        }
        return response()->json(['amount' => $fee]);
    }
} 