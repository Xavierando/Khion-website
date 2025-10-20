<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\OrderStatus;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Rules\isOrderStatus;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

class OrderController extends ApiController
{
    use ApiResponses;

    public function index(Request $request)
    {
        if ($request->user()->isAdmin) {
            $orders = Order::with('order_items')->get();
        } else {
            $orders = $request->user()->orders()->with('order_items')->get();
        }

        return $this->ok('success', ['orders' => OrderResource::collection($orders)]);
    }

    public function show(Request $request, Order $order)
    {
        $order->checkPayementStatus();
        if ($request->user()->isAdmin) {
            return $this->ok('success', ['order' => new OrderResource($order)]);
        } else if ($order->user->id = $request->user()->id) {
            return $this->ok('success', ['order' => new OrderResource($order)]);
        }
        return $this->notAuthorized('unauthorized');
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => [new isOrderStatus]
        ]);

        if ($request->user->isAdmin) {
            $status = $request->enum('status', OrderStatus::class);
            $order->statusUpdate($status);
            return $this->ok('success', ['order' => new OrderResource($order)]);
        }

        return $this->notAuthorized('unauthorized');
    }

    public function delete(Request $request, Order $order)
    {
        if ($request->user->isAdmin && $order->status == OrderStatus::pending) {
            $order->delete();
        }

        return $this->notAuthorized('unauthorized');
    }
}
