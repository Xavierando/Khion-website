<?php

namespace App\Http\Controllers;

use App\Enums\CartStatus;
use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function submit(Request $request)
    {
        $cart = $request->user()->pendingCart()->first();

        $itemsToDelete = $cart->CartItems()->where('quantity', 0)->get();
        $itemsToDelete->each(fn ($i) => $i->delete());

        $items = $cart->CartItems()->with('product')->get();

        $order = Order::create([
            'status' => OrderStatus::pending,
            'total' => $items
                ->reduce(fn ($carry, $item) => $carry + $item['quantity'] * $item['product']['base_price']),
            'user_id' => $request->user()->id,
            'cart_id' => $cart->id,
        ]);

        $cart->status = CartStatus::ordered;
        $cart->save();

        $checkout = $items->mapWithKeys(function ($i) {
            return [
                $i['product']['stripe_price_id'] => intval($i['quantity']),
            ];
        })->toArray();

        return Inertia::location($request->user()->checkout($checkout, [
            'success_url' => route('checkout-success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout-cancel'),
            'metadata' => ['order_id' => $order->id],
        ])->url);
    }

    public function success(Request $request)
    {
        $request->input('session_id');

        $checkoutSession = $request->user()->stripe()->checkout->sessions->retrieve($request->get('session_id'));
        $order_id = $checkoutSession['metadata']['order_id'] ?? null;
        if ($order_id === null) {
            /**
             * if i cant retrive the order id....
             */
        }

        $order = Order::find($order_id);
        $order->status = $checkoutSession['payment_status'];
        $order->save();

        return Inertia::render('checkout/success', [
            'order' => $order_id,
        ]);
    }

    public function cancel()
    {
        return redirect('/cart');
    }
}
