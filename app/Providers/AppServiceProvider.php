<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // View::composer('layouts.nav', function ($view) {
        //     $categories = Category::with('subCategories')->get();
        View::composer('layouts.nav', function ($view) {
            $categories = Category::with(['subCategories' => function ($q) {
                    $q->orderBy('sort_order')->orderBy('name');
                }])
                ->orderByRaw('sort_order IS NULL') // non‑null first, nulls last
                ->orderBy('sort_order')            // 1,2,3,4...
                ->orderBy('name')                  // fallback if same sort_order
                ->get();
            $view->with('categories', $categories);
        });
    }
}
