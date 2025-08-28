<?php

namespace App\Http\Controllers;

use App\Enums\CartStatus;
use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Cashier\Cashier;

class CheckoutController extends Controller
{
    public function submit(Request $request)
    {
        $cart = $request->user()->pendingCart()->first();

        $itemsToDelete = $cart->CartItems()->where('quantity', 0)->get();
        $itemsToDelete->each(fn($i) => $i->delete());

        $items = $cart->CartItems()->with('product')->get();

        $order = Order::create([
            'status' => OrderStatus::pending,
            'total' => $items
                ->reduce(fn($carry, $item) => $carry + $item['quantity'] * $item['product']['base_price']),
            'user_id' => $request->user()->id,
            'cart_id' => $cart->id
        ]);

        $cart->status = CartStatus::ordered;
        $cart->save();

        $checkout = $items->mapWithKeys(function ($i) {
            return [
                $i['product']['stripe_price_id'] => intval($i['quantity']),
            ];
        })->toArray();

        $checkoutSession = $request->user()->checkout($checkout, [
            'success_url' => route('checkout-success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout-cancel'),
            'metadata' => ['order_id' => $order->id],
        ]);

        $order->stripe_checkout_id = $checkoutSession->id;
        $order->save();

        return Inertia::location($checkoutSession->url);
    }

    public function retry(Request $request, Order $order)
    {
        $checkoutSession = Cashier::stripe()->checkout->sessions->retrieve($order->stripe_checkout_id);
        if (!$checkoutSession) {
            return redirect()->back();
        }
        if ($order->status == OrderStatus::pending && $checkoutSession['payment_status'] == 'paid') {
            $order->status = OrderStatus::pending;
            $order->save();
        } else {
            return Inertia::location($checkoutSession->url);
        }
        
        return redirect()->back();
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
