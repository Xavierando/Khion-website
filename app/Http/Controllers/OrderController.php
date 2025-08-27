<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orderByField = DB::raw('FIELD(status, '.substr(array_reduce(OrderStatus::cases(), fn ($carry, $item) => $carry .= ",'".$item->value."'", ''), 1).')');
        $orders = $request->user()->orders()->with('order_items')->orderBy($orderByField)->orderBy('created_at')->paginate(15);

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
