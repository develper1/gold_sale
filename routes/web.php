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
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AccountController;
use App\Services\MetalPriceService;
use App\Models\MetalPrice;

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
Route::get('/coming-soon', function () {
    return view('coming-soon');
})->name('coming-soon');


Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/faq', function () {
    return view('faq');
})->name('faq');

Route::get('/sales-policy', function () {
    return view('privacy-policy');
})->name('sales-policy');

Route::get('/returns-exchanges-policy', function () {
    return view('user-agreement');
})->name('returns-exchanges-policy');

Route::get('/terms-of-sale', function () {
    return view('return-market-policy');
})->name('terms-of-sale');

Route::get('/anti-money-laundering-policy', function () {
    return view('anti-money-laundering-policy');
})->name('anti-money-laundering-policy');

Route::get('/contact', [App\Http\Controllers\ContactController::class, 'show'])->name('contact');
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'submit'])->name('contact.submit');


// Route::get('/thumbs', function () {
//     return view('thumbs');
// });
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

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
        // Allow both POST and DELETE so bulk delete works even if _method=DELETE is present in the request
        Route::match(['post', 'delete'], '/subscribers/bulk-delete', [SubscriberController::class, 'destroyBulk'])
            ->name('subscribers.bulkDelete');
        Route::resource("/subscribers", SubscriberController::class);
        Route::resource('contact-inquiries', App\Http\Controllers\Admin\ContactInquiryController::class)->only(['index', 'show']);
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
        Route::resource('orders', App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show', 'destroy']);
        Route::post('/orders/{order}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::post('/orders/{order}/notes', [App\Http\Controllers\Admin\OrderController::class, 'updateNotes'])->name('orders.updateNotes');
        Route::post('/orders/{order}/cancel', [App\Http\Controllers\Admin\OrderController::class, 'cancelOrder'])->name('orders.cancel');
        Route::post('/orders/{order}/refund', [App\Http\Controllers\Admin\OrderController::class, 'refund'])->name('orders.refund');
        Route::resource('home-sliders', App\Http\Controllers\Admin\HomeSliderController::class);

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

// Email verification (auth required, verified NOT required)
Route::middleware(['auth:web'])->group(function () {
    Route::get('/email/verify', [App\Http\Controllers\Auth\VerificationController::class, 'show'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', function (Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('account.complete-profile');
    })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('/email/verification-notification', [App\Http\Controllers\Auth\VerificationController::class, 'resend'])->middleware('throttle:6,1')->name('verification.send');
});

Route::group(['middleware' => ['auth:web', 'user', 'verified']], function () {
    Route::post('/logout', [UserLoginController::class, 'logout'])->name('logout');
    // Complete profile (no profile.completed - must be accessible before profile is done)
    Route::get('/account/complete-profile', [AccountController::class, 'completeProfile'])->name('account.complete-profile');
    Route::post('/account/complete-profile', [AccountController::class, 'storeCompleteProfile'])->name('account.complete-profile.store');
});

Route::group(['middleware' => ['auth:web', 'user', 'verified', 'profile.completed']], function () {
    Route::get('/checkout', [ShopController::class, 'checkout'])->name('checkout');
    Route::get('/account', [AccountController::class, 'index'])->name('account');
    Route::get('/account/orders/{order}', [AccountController::class, 'showOrder'])->name('account.orders.show');
    Route::get('/account/orders/{order}/ajax', [AccountController::class, 'orderDetailAjax'])->name('account.orders.ajax');

    Route::post('/account/update', [AccountController::class, 'update'])->name('account.update');


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
    $stateFee = \App\Models\StateFee::where('code', $code)->first();
    if (!$stateFee) {
        return response()->json(['amount' => 0, 'fee_type' => 'flat']);
    }
    
    // Get subtotal from request if available
    $subtotal = request('subtotal', 0);
    $calculatedAmount = $stateFee->calculateFee($subtotal);
    
    return response()->json([
        'amount' => $calculatedAmount,
        'fee_type' => $stateFee->fee_type,
        'percentage' => $stateFee->fee_type === 'percentage' ? $stateFee->amount : null
    ]);
})->name('state.fee');

// Add this route for shipping fee by subtotal
Route::get('/shipping-fee/{subtotal}', [\App\Http\Controllers\ShopController::class, 'getShippingFee'])->name('shipping.fee');
// Add this route for service fee by subtotal
Route::get('/service-fee/{subtotal}', [\App\Http\Controllers\ShopController::class, 'getServiceFee'])->name('service.fee');

Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');
Route::get('/order-confirmation/{order}', [OrderController::class, 'confirmation'])->name('order.confirmation');
Route::post('/validate-coupon', [OrderController::class, 'validateCoupon'])->name('coupon.validate');

/**
 * Public JSON endpoint for latest metal prices from DB (used by frontend JS).
 */
Route::get('/metal-prices/latest', function () {
    $base     = 'USD';
    $rates    = [];
    $changes  = [];
    $percents = [];

    MetalPrice::query()
        ->orderByDesc('fetched_at')
        ->get()
        ->groupBy('code')
        ->each(function ($group, $code) use (&$rates, &$changes, &$percents, $base) {
            $latest = $group->first();
            $key = $base . $code;

            $rates[$key] = (float) $latest->price;
            // These may be null for older rows before the columns existed, so default to 0
            $changes[$key]  = isset($latest->change) ? (float) $latest->change : 0.0;
            $percents[$key] = isset($latest->percent) ? (float) $latest->percent : 0.0;
        });

    return response()->json([
        'success' => true,
        'base'     => $base,
        'rates'    => $rates,
        'changes'  => $changes,
        'percents' => $percents,
    ]);
})->name('metal-prices.latest');

/**
 * Manual test route: fetch live metal prices from MetalPrice API
 * and store them in the metal_prices table.
 *
 * Example (GET):
 *   /cron/metal-prices-test?key=YOUR_SECRET_KEY
 *
 * For now this is only intended for manual testing, not as a public endpoint.
 */
Route::get('/cron/metal-prices-test', function (Request $request, MetalPriceService $service) {
    $key = $request->query('key');

    // Simple protection so this isn't called by random visitors
    if ($key !== env('CRON_SECRET_KEY')) {
        abort(403, 'Unauthorized');
    }

    $service->refreshAll();

    return response()->json([
        'success' => true,
        'message' => 'Metal prices fetched and stored.',
    ]);
})->name('cron.metal-prices-test');