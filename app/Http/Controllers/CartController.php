<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\Http\Resources\ProductResource;
use App\Models\Cart_item;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index()
    {
        $cart = Auth::user()->pendingCart()->with(['cart_items'])->first();
        //return $cart;
        //return new CartResource($cart);

        return Inertia::render('Cart', [
            'cart' => new CartResource($cart),
            'products' => ProductResource::collection(Product::all()),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'product' => 'required|exists:products,id',
            'quantity' => 'required|integer:strict'
        ]);
        $cart = Auth::user()->pendingCart()->with(['cart_items'])->first();
        $product = Product::find($validated['product']);
        if ($validated['quantity'] <= $product->availableQuantity()) {
            Cart_item::create([
                'cart_id' => $cart->id,
                'product_id' => $validated['product'],
                'quantity' => $validated['quantity']
            ]);
        }

        return redirect()->back();
        return Inertia::render('Cart', [
            'cart' => $cart,
        ]);
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'product' => 'required|exists:products,id',
            'quantity' => 'required|integer:strict'
        ]);

        $cart = Auth::user()->pendingCart()->with(['cart_items'])->first();
        $product = Product::find($validated['product']);

        if ($validated['quantity'] <= $product->availableQuantity()) {
            Cart_item::create([
                'cart_id' => $cart->id,
                'product_id' => $validated['product'],
                'quantity' => $validated['quantity']
            ]);
        }

        return redirect()->back();
        return Inertia::render('Cart', [
            'cart' => $cart,
        ]);
    }
    public function delete(Request $request)
    {
        Auth::user()->pendingCart()?->delete();
        return Inertia::render('Cart', [
            'cart' => ['items' => []],
        ]);
    }
}
