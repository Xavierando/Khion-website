<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\CartResource;
use App\Models\CartItem;
use App\Models\Product;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

class CartController extends ApiController
{
    use ApiResponses;

    public function index(Request $request)
    {
        $cart = $request->user()->pendingCart()->with(['CartItems'])->first();

        return $this->ok('success', [
            'cart' => new CartResource($cart),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'product' => 'required|exists:products,id',
            'quantity' => 'required|integer:strict',
            'delete' => 'nullable|boolean',
        ]);
        $cart = $request->user()->pendingCart()->with(['CartItems'])->first();
        $product = Product::find($validated['product']);
        $item = $cart->CartItems->first(fn ($v) => $v['product_id'] == $product->id);

        if ($request->has('delete')) {
            $item->delete();
        } else {
            if ($item) {
                if (
                    $validated['quantity'] <= $product->available_quantity + $item->quantity &&
                    $validated['quantity'] >= 0
                ) {
                    if ($item == null) {
                        throw new \Exception('missing item');
                    }
                    $item->quantity = $validated['quantity'];
                    $item->save();
                }
            } else {
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
            }
        }

        $product->updateAvailableQuantity();
        $cart->refresh();

        return $this->ok('success', [
            'cart' => new CartResource($cart),
        ]);
    }
}
