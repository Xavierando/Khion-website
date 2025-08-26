<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomePageController::class, 'index'])->name('home');

Route::resource('products', ProductController::class);

Route::middleware(['auth'])->group(function () {
    Route::put('/dashboard/user', [UserController::class, 'update'])->name('dashboard.user.update');
    Route::get('/dashboard/user', [UserController::class, 'edit'])->name('dashboard.user.edit');
    Route::get('/dashboard', function () {
        return redirect('/dashboard/user');
    })->name('dashboard');

    Route::get('/dashboard/order', [OrderController::class, 'index'])->name('dashboard.order');
    Route::put('/dashboard/order/{order}', [OrderController::class, 'update'])->name('dashboard.order.update');


    Route::get('/dashboard/products', [ProductController::class, 'edit'])->name('dashboard.products');
    Route::post('/dashboard/products', [ProductController::class, 'store']);
    Route::put('/dashboard/products/{product}', [ProductController::class, 'update'])->name('dashboard.products.update');
    Route::delete('/dashboard/products/{product}', [ProductController::class, 'destroy'])->name('dashboard.products.delete');;
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/checkout', [CheckoutController::class, 'submit'])->name('checkout');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout-success');
    Route::get('/checkout/cancel', [CheckoutController::class, 'cancel'])->name('checkout-cancel');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::put('/cart', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart', [CartController::class, 'create'])->name('cart.create');
    Route::delete('/cart', [CartController::class, 'delete'])->name('cart.delete');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
