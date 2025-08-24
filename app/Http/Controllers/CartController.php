<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\Http\Resources\ProductResource;
use App\Models\Cart_item;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = $request->user()->pendingCart()->with(['cart_items'])->first();
        // return $cart;
        // return new CartResource($cart);

        return Inertia::render('Cart', [
            'cart' => new CartResource($cart),
            'products' => ProductResource::collection(Product::all()),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'product' => 'required|exists:products,id',
            'quantity' => 'required|integer:strict',
        ]);
        $cart = $request->user()->pendingCart()->with(['cart_items'])->first();
        $product = Product::find($validated['product']);
        $item = $cart->cart_items->first(fn ($v) => $v['product_id'] == $product->id);

        if (
            $validated['quantity'] <= $product->availableQuantity() + $item->quantity &&
            $validated['quantity'] >= 0
        ) {
            if ($item == null) {
                throw new \Exception('missing item');
            }
            Log::debug($validated['quantity']);
            $item->quantity = $validated['quantity'];
            $item->save();
        }

        $cart->refresh();

        return redirect()->back();

        return Inertia::render('Cart', [
            'cart' => new CartResource($cart),
        ]);
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'product' => 'required|exists:products,id',
            'quantity' => 'required|integer:strict',
        ]);

        $cart = $request->user()->pendingCart()->with(['cart_items'])->first();
        $product = Product::find($validated['product']);

        if ($validated['quantity'] <= $product->availableQuantity()) {
            $item = $cart->cart_items->first(fn ($v) => $v['product_id'] == $product->id);
            if ($item == null) {
                Cart_item::create([
                    'cart_id' => $cart->id,
                    'product_id' => $validated['product'],
                    'quantity' => $validated['quantity'],
                ]);
            } else {
                $item->quantity = $validated['quantity'];
                $item->save();
            }
        }

        return redirect()->back();
    }

    public function delete(Request $request)
    {
        $validated = $request->validate([
            'product' => 'required|exists:products,id',
        ]);

        $cart = $request->user()->pendingCart()->with(['cart_items'])->first();
        $item = $cart->cart_items->first(fn ($v) => $v['product_id'] == $validated['product']);
        $item->delete();

        return Inertia::render('Cart', [
            'cart' => new CartResource($cart),
        ]);
    }
}
