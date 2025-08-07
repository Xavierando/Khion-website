<?php

use App\Enums\OrderStatus;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Models\Order;
use App\Models\Order_item;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomePageController::class, 'index'])->name('home');

Route::resource('products', ProductController::class);
Route::put('user', [UserController::class, 'update']);
Route::get('/cart', function () {
    return Inertia::render('Cart');
})->name('cart');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/checkout', function (Request $request) {
    $stripePriceId = 'price_1Rt5ZiRxtEVoFjKik3t2Ugrx';

    $products = $request->input('products');

    $orders = array_map(fn ($i) => Product::find($i['id'])->stripe_price_id, $products);

    $order = Order::create([
        'status' => OrderStatus::pending,
        'total' => 0,
        'user_id' => $request->user()->id,
    ]);

    $checkout = [];
    foreach ($products as $product) {
        $stripe_price_id = Product::find($product['id'])->stripe_price_id;
        Order_item::create([
            'order_id' => $order->id,
            'product_id' => $product['id'],
            'price' => $product['price'],
            'stripe_price_id' => $stripe_price_id,
            'configuration' => ['options' => $product['options']],
        ]);

        if (isset($checkout[$stripe_price_id])) {
            $checkout[$stripe_price_id] += 1;
        } else {
            $checkout[$stripe_price_id] = 1;
        }
    }

    return Inertia::location($request->user()->checkout($checkout, [
        'success_url' => route('checkout-success').'?session_id={CHECKOUT_SESSION_ID}&order='.$order->id,
        'cancel_url' => route('checkout-cancel'),
    ])->url);
})->name('checkout');

Route::get('/checkout/success', function (Request $request) {
    $request->input('session_id');

    $checkoutSession = $request->user()->stripe()->checkout->sessions->retrieve($request->get('session_id'));
    $checkoutSession['success_url'];

    preg_match('/order=([0-9]*)/', $checkoutSession['success_url'], $matches, PREG_OFFSET_CAPTURE);

    $order = Order::find($matches[1][0]);
    $order->status = $checkoutSession['payment_status'];
    $order->save();

    return Inertia::render('checkout/success', [
        'order' => $matches[1][0],
    ]);
})->name('checkout-success');
Route::view('/checkout/cancel', 'checkout/cancel')->name('checkout-cancel');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
