<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = $request->user()->orders()->with('order_items')->paginate(15);

        return Inertia::render('dashboard/orders/index', [
            'orders' => OrderResource::collection($orders),
        ]);
    }

    public function update(Request $request, Order $order)
    {

        $user = $request->user();
        Log::debug($user->isAdmin);
        if (! $user->isAdmin && $user->id != $order->user_id) {
            return redirect()->back();
        }

        if ($request->has('status') && $user->isAdmin) {
            $status = $request->enum('status', OrderStatus::class);
            $order->statusUpdate($status);
        }

        return redirect()->route('dashboard.order');
    }
}
