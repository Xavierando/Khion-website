<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomePageController::class, 'index'])->name('home');

Route::resource('products', ProductController::class);
Route::get('/cart', function () {
    return Inertia::render('Cart', [
        'products' => ProductResource::collection(Product::all()),
    ]);
})->middleware(['auth', 'verified'])->name('cart');

Route::middleware(['auth'])->group(function () {
    Route::put('/dashboard/user', [UserController::class, 'update'])->name('dashboard.user.update');
    Route::get('/dashboard/user', [UserController::class, 'edit'])->name('dashboard.user.edit');
    Route::get('/dashboard', function () {
        return redirect('/dashboard/user');
    })->name('dashboard');

    Route::get('/dashboard/order', [OrderController::class, 'index'])->name('dashboard.order.index');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/checkout', [CheckoutController::class, 'submit'])->name('checkout');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout-success');
    Route::get('/checkout/cancel', [CheckoutController::class, 'cancel'])->name('checkout-cancel');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
