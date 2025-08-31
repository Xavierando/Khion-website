<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\Http\Resources\ProductResource;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = $request->user()->pendingCart()->with(['CartItems'])->first();
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
        $cart = $request->user()->pendingCart()->with(['CartItems'])->first();
        $product = Product::find($validated['product']);
        $item = $cart->CartItems->first(fn ($v) => $v['product_id'] == $product->id);

        if (
            $validated['quantity'] <= $product->available_quantity + $item->quantity &&
            $validated['quantity'] >= 0
        ) {
            if ($item == null) {
                throw new \Exception('missing item');
            }
            Log::debug($validated['quantity']);
            $item->quantity = $validated['quantity'];
            $item->save();
        }

        $product->updateAvailableQuantity();
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

        $cart = $request->user()->pendingCart()->with(['CartItems'])->first();
        $product = Product::find($validated['product']);

        if ($validated['quantity'] <= $product->available_quantity) {
            $item = $cart->CartItems->first(fn ($v) => $v['product_id'] == $product->id);
            if ($item == null) {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $validated['product'],
                    'quantity' => $validated['quantity'],
                ]);
            } else {
                $item->quantity = $validated['quantity'];
                $item->save();
            }
        }

        $product->updateAvailableQuantity();

        return redirect()->back();
    }

    public function delete(Request $request, Product $product)
    {
        $cart = $request->user()->pendingCart()->with(['CartItems'])->first();
        $item = $cart->CartItems->first(fn ($v) => $v['product_id'] == $product->id);
        $item->delete();

        $product->updateAvailableQuantity();

        return Inertia::render('Cart', [
            'cart' => new CartResource($cart),
        ]);
    }
}
