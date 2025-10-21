<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Tag;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

class ProductController extends ApiController
{
    use ApiResponses;

    /**
     * Display a listing of all products.
     */
    public function index()
    {
        $products = ProductResource::collection(Product::all());

        return $this->ok('success', ['products' => ProductResource::collection($products)]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (! $request->user()->isAdmin) {
            return $this->notAuthorized('unauthorized');
        }

        $product = new Product;
        $product->code = uniqid();
        $product->name = uniqid();
        $product->description = 'awesom description';
        $product->base_price = 0;
        $product->quantity = 0;
        $product->available_quantity = 0;
        $product->configuration = '{"options":[]}';
        $product->genereteStripeID();
        $product->save();

        return $this->ok('success', ['product' => new ProductResource($product)]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Product $product)
    {
        return $this->ok('success', ['product' => new ProductResource($product)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        if (! $request->user()->isAdmin) {
            return $this->notAuthorized('unauthorized');
        }

        $product->code = $request->input('code');

        if (
            $product->base_price !== intval($request->input('base_price'))
        ) {
            $product->base_price = $request->input('base_price');
            $product->updateStripePrice();
        }

        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->updateStripeInfo();

        $product->quantity = $request->input('quantity');
        $product->updateAvailableQuantity();

        $product->tags()->detach();
        if ($request->has('tags')) {
            foreach ($request->input('tags') as $tag) {
                $tag = Tag::find($tag['id']);
                if ($tag) {
                    $product->tags()->attach($tag);
                }
            }
        }

        $product->save();

        return $this->ok('success', [
            'product' => new ProductResource($product),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Product $product)
    {
        if (! $request->user()->isAdmin) {
            return $this->notAuthorized('unauthorized');
        }

        if (! $request->user()->isAdmin) {
            return redirect()->back();
        }

        $product->delete();

        return $this->ok('deleted');
    }
}
