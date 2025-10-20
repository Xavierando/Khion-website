<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\CartStatus;
use App\Enums\OrderStatus;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class CheckoutController extends ApiController
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
            'cart_id' => $cart->id,
        ]);

        $cart->status = CartStatus::ordered;
        $cart->save();
        
        $checkoutSession = $order->createCheckoutSession();

        $order->stripe_checkout_id = $checkoutSession->id;
        $order->save();

        return $this->ok('success', ['url' => $checkoutSession->url]);
    }

    public function retry(Order $order)
    {
        
        return $this->ok('success', ['url' => $order->checkoutUrl()]);

    }
}
