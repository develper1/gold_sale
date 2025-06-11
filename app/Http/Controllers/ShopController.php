<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    public function index()
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
        
        $products = Product::where('is_active', true)
                         ->with('images')
                         ->get();
        return view('thumbs', compact('categories', 'products'));
    }

    public function subcategory($slug)
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
        $products = Product::where('sub_category_id', $subcategory->id)
                         ->where('is_active', true)
                         ->with('images')
                         ->get();
        
        return view('thumbs', compact('categories', 'subcategory', 'products'));
    }

    public function category($slug)
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
        
        // Get all products for this category's subcategories
        $products = Product::whereHas('subCategory', function($query) use ($category) {
            $query->where('category_id', $category->id);
        })
        ->where('is_active', true)
        ->with(['images', 'subCategory'])
        ->get()
        ->groupBy('sub_category_id');
        
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
        
        return view('product-detail', compact('categories', 'product'));
    }
} 