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

Route::get('/landingpage', function () {
    return view('landingpage.index');
});

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

        Route::resource("/users", UserController::class);
        Route::resource("/products", ProductController::class);

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

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::post('/logout', [App\Http\Controllers\Auth\UserLoginController::class, 'logout'])->name('logout');

});

Route::post('/subscriber', [App\Http\Controllers\SubscriberController::class, 'store'])->name('subscriber.store');
Route::post('/subscriber/detail', [App\Http\Controllers\SubscriberController::class, 'storeDetail'])->name('subscriber.storeDetail');