<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Order_item;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function submit(Request $request)
    {
        $products = $request->input('products');

        $order = Order::create([
            'status' => OrderStatus::pending,
            'total' => 0,
            'user_id' => $request->user()->id,
        ]);

        $checkout = [];
        $total = 0;
        foreach ($products as $product) {
            $stripe_price_id = Product::find($product['id'])->stripe_price_id;
            $total += $product['price'];
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
        $order->total = $total;
        $order->save();

        return Inertia::location($request->user()->checkout($checkout, [
            'success_url' => route('checkout-success').'?session_id={CHECKOUT_SESSION_ID}&order='.$order->id,
            'cancel_url' => route('checkout-cancel'),
        ])->url);
    }

    public function success(Request $request)
    {
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
    }

    public function cancel()
    {
        return redirect('/cart');
    }
}
