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
});

Route::get('/landing', function () {
    return view('index');
});

Route::get('/home', function () {
    return view('home');
});

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
        Route::resource("/products", ProductController::class);
        Route::resource('/coupons', \App\Http\Controllers\Admin\CouponController::class);
        Route::resource('shipping', ShippingController::class);
        Route::resource('services', ServiceController::class);
        Route::resource('statefee', StateFeeController::class)->only(['index', 'edit', 'update'])->parameters([
            'statefee' => 'stateFee'
        ]);
        Route::resource('categories', CategoryController::class);
        Route::get('/sub-categories/{categoryId}', [SubCategoryController::class, 'getSubCategoriesByCategory'])->name('sub-categories.getSubCategoriesByCategory');
        Route::resource('sub-categories', SubCategoryController::class)->except(['show']);

    });
});

// Auth::routes();
Route::middleware([GuestUserMiddleware::class])->group(function(){

    Route::get('/login', [App\Http\Controllers\Auth\UserLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\UserLoginController::class, 'login'])->name('login.submit');

    Route::get('/register', [App\Http\Controllers\Auth\UserRegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [App\Http\Controllers\Auth\UserRegisterController::class, 'register'])->name('register.submit');
});

Route::group(['middleware' => ['auth:web', 'user']], function () {

    // Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::post('/logout', [App\Http\Controllers\Auth\UserLoginController::class, 'logout'])->name('logout');

});

// Email Testing Routes
Route::get('/email-test', [EmailTestController::class, 'showTestForm'])->name('email.test');
Route::post('/send-test-email', [EmailTestController::class, 'sendTestEmail'])->name('send.test.email');
