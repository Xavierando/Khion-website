<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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

        if ($request->has(['name', 'role', 'bio'])) {
            $validated = $request->validate([
                'name' => 'required|string',
                'role' => 'string|nullable',
                'bio' => 'string|nullable',
            ]);
            $user->update($validated);
        }

        if ($request->has('oldpsw', 'newpsw')) {
            $validated = $request->validate([
                'oldpsw' => 'required|string',
                'newpsw' => 'required|string|min:8',
            ]);
            if (Hash::check($validated['oldpsw'], $user->password)) {
                $user->update(['password' => Hash::make($validated['newpsw'])]);
            }
        }

        if ($request->hasFile('pic')) {
            $request->validate([
                'pic' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:512',
            ]);

            $path = Storage::disk('images')->put('pic/', $request->file('pic'));
            Storage::disk('images')->delete($user->imageUrl);
            $user->update(['imageUrl' => $path]);
        }

        return redirect()->route('dashboard.user.edit');
    }
}
