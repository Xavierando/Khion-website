<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OrderController extends Controller
{
    use ApiResponses;

    public function index(Request $request)
    {
        $orderByField = DB::raw('FIELD(status, ' . substr(array_reduce(OrderStatus::cases(), fn($carry, $item) => $carry .= ",'" . $item->value . "'", ''), 1) . ')');
        if ($request->user()->isAdmin) {
            $orders = Order::with('order_items')->orderBy($orderByField)->orderBy('created_at')->paginate(15);
        } else {
            $orders = $request->user()->orders()->with('order_items')->orderBy($orderByField)->orderBy('created_at')->paginate(15);
        }

        return $this->ok('success', ['orders' => OrderResource::collection($orders)]);
    }

    public function update(Request $request, Order $order)
    {

        $user = $request->user();

        if ($request->has('status') && $user->isAdmin) {
            $status = $request->enum('status', OrderStatus::class);
            $order->statusUpdate($status);
            return $this->ok('success', ['order' => new OrderResource($order)]);
        }

        return $this->notAuthorized('unauthorized');
    }

    public function delete(Request $request, Order $order)
    {
        $user = $request->user();
        
        if ($user->isAdmin && $order->status == OrderStatus::pending) {
            $order->delete();
        }

        return $this->notAuthorized('unauthorized');
    }
}
