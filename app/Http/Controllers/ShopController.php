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
        $productsQuery = Product::where('is_active', true)->with(['images', 'subCategory']);
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
        return view('thumbs', compact('categories', 'products'));
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
            ->with('images');
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
        
        return view('thumbs', compact('categories', 'subcategory', 'products'));
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
        ->with(['images', 'subCategory']);
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
        
        return view('thumbs', compact('categories', 'category', 'products'));
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
                        ->with(['images', 'subCategory'])
                        ->firstOrFail();
        $setting = \App\Models\Setting::first();
        $credit_card_percentage = $setting ? $setting->credit_card_percentage : 0;
        return view('product-detail', compact('categories', 'product', 'credit_card_percentage'));
    }

    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);
        
        $product = Product::with('images')->findOrFail($productId);
        
        $cart = session()->get('cart', []);
        
        if(isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->current_price,
                'pricing_type' => $product->pricing_type,
                'quantity' => $quantity,
                'image' => $product->images->first() ? $product->images->first()->image_path : null,
                'slug' => $product->slug,
                'use_tier_pricing' => $product->use_tier_pricing
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
        $total = 0;
        
        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return view('cart', compact('cart', 'total'));
    }

    public function updateCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');
        $cart = session()->get('cart', []);

        if(isset($cart[$productId])) {
            $product = Product::findOrFail($productId);
            
            // Get the correct price based on quantity
            if ($product->use_tier_pricing) {
                $price = $product->getTierPriceForQuantity($quantity);
            } else if ($product->use_spot_tier_pricing && $product->pricing_type === 'spot') {
                $tier = $product->getSpotTierPriceForQuantity($quantity);
                $metalPriceService = app(\App\Services\MetalPriceService::class);
                $spotPrice = $metalPriceService->getSpotPrice($product->product_type);
                if ($tier) {
                    if ($tier->type === 'percentage') {
                        $price = $spotPrice + ($spotPrice * ($tier->value / 100));
                    } else { // fixed
                        $price = $spotPrice + $tier->value;
                    }
                } else {
                    // fallback to blanket markup if no tier found
                    $markupPercentage = $product->blanket_markup_percentage;
                    $price = $spotPrice;
                    if ($markupPercentage) {
                        $price = $spotPrice * (1 + ($markupPercentage / 100));
                    }
                }
            } else {
                $price = $product->current_price;
            }
            
            $cart[$productId]['quantity'] = $quantity;
            $cart[$productId]['price'] = $price;
            $cart[$productId]['use_tier_pricing'] = $product->use_tier_pricing;
            session()->put('cart', $cart);
            
            // Calculate new totals
            $total = 0;
            foreach($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }

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

        if(isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
            
            // Calculate new totals
            $total = 0;
            foreach($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }

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
        $product = Product::with(['images', 'subCategory'])
                         ->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'product' => $product
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
} 