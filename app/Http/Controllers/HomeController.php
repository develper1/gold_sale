<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HomeSlider;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $sliders = HomeSlider::orderBy('order')->get();
        $categoriesWithProduct = \App\Models\Category::whereHas('products', function($q) {
            $q->where('is_active', 1);
        })
        ->with(['products' => function($q) {
            $q->where('is_active', 1)->inRandomOrder()->with('images');
        }])
        ->inRandomOrder()
        ->limit(3)
        ->get();
        $featuredProducts = \App\Models\Product::where('is_active', 1)->where('is_featured', 1)
        ->with('images')
        ->inRandomOrder()
        ->limit(5)
        ->get();
        $bestSellerProducts = \App\Models\Product::where('is_active', 1)->where('is_best_seller', 1)->with('images')
        ->inRandomOrder()
        ->limit(5)
        ->get();
        return view('home', compact('sliders', 'categoriesWithProduct', 'featuredProducts', 'bestSellerProducts'));
    }
}
