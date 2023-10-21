<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\SaleDetailController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ShoppingCartController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\VerificationController;

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

Route::get('/shopping/pay', [ShoppingCartController::class, 'pay'])->name('shopping.pay');

Route::post('/shopping/finalize-purchase', [ShoppingCartController::class, 'finalizePurchase'])->name('shopping.finalize-purchase');

/*  ============================  shopping   ============================    */
Route::controller(HomeController::class)->group(function () {
    Route::get('', 'index')->name('home');
    Route::post('api-product-calculation', 'productCalculation')->name('products.calculation');
});
Route::controller(ContactController::class)->prefix('contact')->group(function () {
    Route::get('', 'index')->name('contact.index');
    Route::post('/send-email', 'sendEmail')->name('contact.send-email');
});
Route::get('products/{product}/image-gallery', [ProductController::class, 'imageGallery'])->name('products.imageGallery');

Route::controller(ShoppingCartController::class)->prefix('shopping')->group(function () {
    Route::get('cart', 'index')->name('shopping.cart');
    Route::get('pay', 'pay')->name('shopping.pay')->middleware('auth');
});

Route::post('shopping/save-payment', 'ShoppingCartController@savePayment')->name('shopping.save-payment');
/*  =====================================================================    */

Route::get('verification', [VerificationController::class, 'index'])->name('verification.index');
Route::post('verify-code', [AuthController::class, 'verifyCode'])->name('verify.code');

Route::controller(AuthController::class)->middleware('guest')->group(function () {
    //login
    Route::get('login', 'loginIndex')->name('login.index');
    Route::post('login', 'login')->name('login');
    //register
    Route::get('register', 'registerIndex')->name('register.index');
    Route::post('register', 'register')->name('register');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('check.if.admin')->prefix('admin')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('dashboard/data', [DashboardController::class, 'dashboard'])->name('dashboard.data');

        //products
        Route::resource('products', ProductController::class);
        Route::get('products-list', [ProductController::class, 'list'])->name('product.list');
        Route::post('product/images/{product}', [ProductController::class, 'images']);
        Route::post('product/delete', [ProductController::class, 'deleteImages']);
        Route::get('images/{id}', [ProductController::class, 'showImages']);

        //users
        Route::resource('users', UserController::class);
        Route::get('users-list', [UserController::class, 'list'])->name('users.list');
        Route::get('user/image/{id}', [UserController::class, 'showImage']);
        Route::post('user/image/{user}', [UserController::class, 'image']);
        Route::post('user-image/delete', [UserController::class, 'deleteImage']);

        //purchases
        Route::resource('purchases', PurchaseController::class);
        Route::get('purchases-list', [PurchaseController::class, 'list'])->name('purchases.list');
        Route::get('purchase/image/{id}', [PurchaseController::class, 'showImage']);
        Route::post('purchase/image/{purchase}', [PurchaseController::class, 'image']);
        Route::post('purchase-image/delete', [PurchaseController::class, 'deleteImage']);

        //Settings
        Route::resource('settings', SettingController::class);
        Route::get('settings-list', [SettingController::class, 'list'])->name('settings.list');

        //Sales
        Route::controller(SaleController::class)->group(function () {
            Route::resource('sales', SaleController::class);
            Route::get('sales-list', 'listSales')->name('sales.list');
            Route::get('tracking', 'tracking')->name('tracking');
            Route::post('update-tracking/{sale}', 'updateTracking');
            Route::get('billing/{sale}', 'billing')->name('billing');
        });

        //Sale Details
        Route::resource('sale-details', SaleDetailController::class);
    });

    //Sales Customers
    Route::apiResource('api-sales', SaleController::class);
    Route::get('shopping/end-of-order/{sale}', [ShoppingCartController::class, 'endOfOrder'])->name('shopping.end-of-order');
});
