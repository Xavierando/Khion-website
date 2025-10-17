<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\CartStatus;
use App\Enums\OrderStatus;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Laravel\Cashier\Cashier;

class CheckoutController extends ApiController
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

        $checkoutSession = $request->user()->checkout($checkout, [
            'success_url' => 'http://127.0.0.1//checkout/success',
            'cancel_url' => 'http://127.0.0.1//checkout/success',
            'metadata' => ['order_id' => $order->id],
        ]);

        $order->stripe_checkout_id = $checkoutSession->id;
        $order->save();

        return $this->ok('success',['url' => $checkoutSession->url]);

    }

    public function retry(Request $request, Order $order)
    {
        $checkoutSession = Cashier::stripe()->checkout->sessions->retrieve($order->stripe_checkout_id);
        if (! $checkoutSession) {
            return redirect()->back();
        }
        if ($order->status == OrderStatus::pending && $checkoutSession['payment_status'] == 'paid') {
            $order->status = OrderStatus::pending;
            $order->save();
        } else {
            return $this->ok('success',['url' => $checkoutSession->url]);
        }

        return $this->notAuthorized('');
    }

    public function check(Request $request)
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

        return $this->ok('success',['order' => new OrderResource($order)]);

    }

    public function cancel()
    {
        return redirect('/cart');
    }
}
