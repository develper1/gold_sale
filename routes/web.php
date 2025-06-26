<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\AdminHomeController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Middleware\UserMiddleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\GuestAdminMiddleware;
use App\Http\Middleware\GuestUserMiddleware;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\EmailTestController;
use App\Http\Controllers\Admin\ShippingController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\StateFeeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\Admin\PriceTierRangeController;
use App\Http\Controllers\Auth\UserLoginController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\SpotTierPriceController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\UserRegisterController;
use App\Models\Product;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index');
})->name('index');

Route::get('/landing', function () {
    return view('index');
});

Route::get('/home', function () {
    return view('home');
})->name('home');

Route::get('/account', function () {
    return view('my-account');
})->name('account');
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/faq', function () {
    return view('faq');
})->name('faq');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');


// Route::get('/thumbs', function () {
//     return view('thumbs');
// });

Route::post('/subscriber', [SubscriberController::class, 'store'])->name('subscriber.store');
Route::post('/subscriber/detail', [SubscriberController::class, 'storeDetail'])->name('subscriber.storeDetail');

Route::prefix('admin')->name('admin.')->group(function(){

    Route::middleware([GuestAdminMiddleware::class])->group(function(){

        Route::controller(AdminAuthController::class)->group(function () {
            Route::get('/login', 'index');
            Route::post('/login','login')->name('login');
        });

    });

    Route::group(['middleware' => ['auth:admin', 'admin']], function () {

        Route::get('/home', [AdminHomeController::class, 'index'])->name('home');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/users', [UserController::class, 'users'])->name('users');
        Route::get('/subscribers/export', [SubscriberController::class, 'export'])->name('subscribers.export');
        Route::resource("/subscribers", SubscriberController::class);
        Route::resource("/users", UserController::class);
        Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
        Route::resource("/products", ProductController::class)->except(['show']);
        Route::resource('/coupons', CouponController::class);
        Route::resource('shipping', ShippingController::class);
        Route::resource('services', ServiceController::class);
        Route::resource('statefee', StateFeeController::class)->only(['index', 'edit', 'update'])->parameters([
            'statefee' => 'stateFee'
        ]);
        Route::resource('categories', CategoryController::class);
        Route::get('/get-sub-categories/{categoryId}', [SubCategoryController::class, 'getSubCategoriesByCategory'])->name('sub-categories.getSubCategoriesByCategory');
        Route::resource('sub-categories', SubCategoryController::class);
        Route::resource('price-tier-ranges', PriceTierRangeController::class);
        Route::resource('spot-tier-prices', SpotTierPriceController::class);
        Route::resource('settings', SettingsController::class)->only(['index', 'update']);

    });
});

// Auth::routes();
Route::middleware([GuestUserMiddleware::class])->group(function(){

    Route::get('/login', [UserLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [UserLoginController::class, 'login'])->name('login.submit');

    Route::get('/forget-password', [UserLoginController::class, 'showForgetPassForm'])->name('forget-password');
    Route::post('/forget-password', [UserLoginController::class, 'submitForgetPassword'])->name('forget-password.submit');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

    Route::get('/register', [UserRegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [UserRegisterController::class, 'register'])->name('register.submit');
});

Route::group(['middleware' => ['auth:web', 'user']], function () {

    // Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::post('/logout', [UserLoginController::class, 'logout'])->name('logout');
    Route::get('/checkout', [ShopController::class, 'checkout'])->name('checkout');


});

// Email Testing Routes
Route::get('/email-test', [EmailTestController::class, 'showTestForm'])->name('email.test');
Route::post('/send-test-email', [EmailTestController::class, 'sendTestEmail'])->name('send.test.email');

// Shop Routes
Route::get('/product/{slug}', [ShopController::class, 'product'])->name('shop.product');
Route::get('/thumbs', [ShopController::class, 'index'])->name('shop.index');
Route::get('/thumbs/category/{slug}', [ShopController::class, 'category'])->name('shop.category');
Route::get('/thumbs/{slug}', [ShopController::class, 'subcategory'])->name('shop.subcategory');
Route::get('/shop/product/{id}/quick-view', [ShopController::class, 'quickView'])->name('shop.quick-view');

// Cart Routes
Route::post('/cart/add', [ShopController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update', [ShopController::class, 'updateCart'])->name('cart.update');
Route::post('/cart/remove', [ShopController::class, 'removeFromCart'])->name('cart.remove');
Route::get('/cart/clear', [ShopController::class, 'clearCart'])->name('cart.clear');
Route::get('/cart/count', [ShopController::class, 'getCartCount'])->name('cart.count');
Route::get('/cart', [ShopController::class, 'viewCart'])->name('cart.view');

Route::get('/products/{product}/tier-prices-modal', [ShopController::class, 'getTierPricesModal'])->name('product.tier_prices_modal');

Route::get('/admin/products/spot-price', function (\Illuminate\Http\Request $request) {
    $type = $request->input('type');
    if (!$type) return response()->json(['success' => false, 'message' => 'Type required'], 400);
    $metalPriceService = app(\App\Services\MetalPriceService::class);
    $spotPrice = $metalPriceService->getSpotPrice($type);
    return response()->json(['success' => true, 'spot_price' => $spotPrice]);
})->name('admin.products.spot_price');

Route::get('/state-fee/{code}', function ($code) {
    $fee = \App\Models\StateFee::where('code', $code)->value('amount');
    return response()->json(['amount' => $fee ?? 0]);
})->name('state.fee');