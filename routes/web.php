<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\ProductsController;

use App\Http\Controllers\UserController;

// Show the form to request a password reset link
Route::get('forgot-password', [UserController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [UserController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [UserController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [UserController::class, 'resetPassword'])->name('password.update');

// Authentication routes
Route::get('auth/google', [GoogleAuthController::class, 'redirect'])->name('google-auth');
Route::get('auth/google/callback', [GoogleAuthController::class, 'callbackGoogle']);
Route::get('auth/facebook', [GoogleAuthController::class, 'redirectToFacebook']);
Route::get('auth/facebook/callback', [GoogleAuthController::class, 'handleFacebookCallback']);
Route::get("/login", [GoogleAuthController::class, 'login'])->name('login');
Route::post("auth", [GoogleAuthController::class, 'auth']);



// Frontend Section

Route::get('customer', [GoogleAuthController::class, 'signup'])->name('customer.store');
Route::post('customer', [GoogleAuthController::class, 'store'])->name('customer.store');
Route::get("/admin/orderlist", [AdminController::class, 'orderlist']);
Route::get("/products/{name}/{id}", [FrontendController::class, 'show']);
Route::get("show/products", [FrontendController::class, 'index'])->name('products.index');
Route::get("/", [FrontendController::class, 'index'])->name('home');

Route::get("/shopping", [FrontendController::class, 'shop'])->name('shop');

Route::middleware(['auth'])->group(function () {
    Route::post('cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('cart/updateQuantity/{id}', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
    Route::post('cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('cart/show', [CartController::class, 'show'])->name('cart.show');
    Route::get('order/{name}/{id}', [CartController::class, 'index'])->name('order.create');
    Route::post('/place-order', [CartController::class, 'store'])->name('place.order');
    Route::get('logout', [FrontendController::class, 'logout'])->name('logout');
});






#Backend Section
Route::get("/admin", [AdminController::class, 'index']);
Route::post("/admin", [AdminController::class, 'login'])->name('admin');
Route::middleware(['admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::resource('brands', BrandController::class);
    Route::get('/admin/products', [ProductsController::class, 'index'])->name('product.index');
    Route::get('/admin/products/create', [ProductsController::class, 'create'])->name('product.create');
    Route::post('/admin/products', [ProductsController::class, 'store'])->name('product.store');
    Route::get('/admin/products/{product}/edit', [ProductsController::class, 'edit'])->name('product.edit');
    Route::put('/admin/products/update/{product}', [ProductsController::class, 'update'])->name('product.update');
    Route::delete('/admin/products/{id}', [ProductsController::class, 'destroy'])->name('product.destroy');


    Route::get('/admin/banners', [BannerController::class, 'index'])->name('banner.index');
    Route::get('/admin/banners/create', [BannerController::class, 'create'])->name('banner.create');
    Route::post('/admin/banners', [BannerController::class, 'store'])->name('banner.store');
    Route::delete('/admin/banners/{banner}', [BannerController::class, 'destroy'])->name('banner.destroy');

    Route::resource('brand', BrandController::class);
    Route::resource('categories', CategoryController::class);
    Route::post("/admin/logout", [AdminController::class, 'logout'])->name('admin.logout');




    Route::post('/generate-seo', [AdminController::class, 'generateSeo'])
        ->name('product.generateSeo');



    Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders');
    Route::post('/orders/items/{orderItem}/verify', [OrderController::class, 'verify'])->name('admin.orders.verify');
    Route::post('/orders/items/{orderItem}/ship', [OrderController::class, 'ship'])->name('admin.orders.ship');
    Route::post('/orders/items/{orderItem}/deliver', [OrderController::class, 'deliver'])->name('admin.orders.deliver');


    Route::delete('/admin/orders/{order}', [OrderController::class, 'destroy'])->name('admin.orders.delete');
});
