<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\CartResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = ProductResource::collection(Product::all());

        return Inertia::render('Products/index', ['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        if (! $request->user()->isAdmin) {
            return redirect()->back();
        }

        $Product = new Product;
        $Product->code = $request->input('code');
        $Product->name = $request->input('name');
        $Product->description = $request->input('description');
        $Product->base_price = $request->input('base_price');
        $Product->quantity = $request->input('quantity');
        $Product->configuration = '{"options":[]}';
        $Product->genereteStripeID();
        $Product->save();

        $i = 0;
        while ($request->hasFile('images.'.$i) && $i < 10) {
            $path = Storage::disk('images')->put('/product', $request->file('images.'.$i));

            ProductGallery::create([
                'product_id' => $Product->id,
                'fsname' => $path,
            ]);

            $i++;
        }

        return Inertia::render('dashboard/products/edit', ['status' => 'success']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Product $product)
    {
        $cart = $request?->user()->pendingCart()->with(['CartItems'])->first() ?? [];

        return Inertia::render('Products/show', [
            'product' => ProductResource::make($product),
            'cart' => new CartResource($cart),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return Inertia::render('dashboard/products/edit', [
            'products' => ProductResource::collection(Product::all()),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        if (! $request->user()->isAdmin) {
            return redirect()->back();
        }

        if ($request->input('update') == 'code' && $request->has('code')) {
            $product->code = $request->input('code');
        }

        if ($request->input('update') == 'name' && $request->has('name')) {
            $product->name = $request->input('name');
            $product->updateStripeInfo();
        }

        if ($request->input('update') == 'description' && $request->has('description')) {
            $product->description = $request->input('description');
            $product->updateStripeInfo();
        }

        if ($request->input('update') == 'base_price' && $request->has('base_price')) {
            $product->base_price = $request->input('base_price');
            $product->updateStripePrice();
        }

        if ($request->input('update') == 'quantity' && $request->has('quantity')) {
            $product->quantity = $request->input('quantity');
        }

        $product->save();

        $i = 0;
        $path = '';
        while ($request->hasFile('images.'.$i) && $i < 10) {
            $path = Storage::disk('images')->put('product/', $request->file('images.'.$i));

            ProductGallery::create([
                'product_id' => $product->id,
                'fsname' => $path,
            ]);

            $i++;
        }

        if ($request->input('update') == 'image' && $request->has('deleteImage')) {
            $fsname = substr($request->input('deleteImage'), strlen('/images/'));
            Log::debug($fsname);
            $image = ProductGallery::firstWhere('fsname', $fsname);
            if ($image) {
                $image->delete();
                Storage::disk('images')->delete($request->input('deleteImage'));
            }
        }

        return Inertia::render('dashboard/products/edit', ['status' => 'success', 'imgpath' => $path]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Product $product)
    {
        if (! $request->user()->isAdmin) {
            return redirect()->back();
        }

        $product->delete();

        return redirect()->route('dashboard.products');
    }
}
