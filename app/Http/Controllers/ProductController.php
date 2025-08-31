<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\CartResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductGallery;
use App\Models\Tag;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

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

        $product = new Product;
        $product->code = $request->input('code');
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->base_price = $request->input('base_price');
        $product->quantity = $request->input('quantity');
        $product->configuration = '{"options":[]}';
        $product->genereteStripeID();
        $product->save();
        $product->updateAvailableQuantity();

        $i = 0;
        while ($request->hasFile('images.'.$i) && $i < 10) {
            $manager = new ImageManager(new Driver);
            $image = $manager->read($request->file('images.'.$i));
            $temp_file = sys_get_temp_dir().'/thumbnail';
            $image->scaleDown(1200, 1200)->toJpeg(90)->save($request->file('images.'.$i));
            $image->scaleDown(350, 350)->toJpeg(90)->save($temp_file);

            $paththumb = Storage::disk('images')->put('product/thumbnail/', new File($temp_file));
            $pathfull = Storage::disk('images')->put('product/', $request->file('images.'.$i));

            ProductGallery::create([
                'product_id' => $product->id,
                'src' => $pathfull,
                'thumbnail' => $paththumb,
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
        $cart = $request?->user()?->pendingCart()->with(['CartItems'])->first();

        return Inertia::render('Products/show', [
            'product' => ProductResource::make($product),
            'cart' => ($cart) ? new CartResource($cart) : ['items' => []],
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
            $product->updateStripePrice();
        }

        if ($request->input('update') == 'description' && $request->has('description')) {
            $product->description = $request->input('description');
            $product->updateStripePrice();
        }

        if ($request->input('update') == 'base_price' && $request->has('base_price')) {
            $product->base_price = $request->input('base_price');
            $product->updateStripePrice();
        }

        if ($request->input('update') == 'quantity' && $request->has('quantity')) {
            $product->quantity = $request->input('quantity');
            $product->updateAvailableQuantity();
        }

        $tag = ['tag' => ''];
        if ($request->input('update') == 'tag' && $request->has('tag')) {
            $tag = Tag::find($request->input('tag'));
            $haveTag = $product->tags()->where('id', $tag->id)->count();
            if ($haveTag == 0) {
                $product->tags()->attach($tag);
                $tag = ['tag' => $tag];
            } else {
                $product->tags()->detach($tag);
                $tag = ['tag' => ''];
            }
        }

        $product->save();

        $i = 0;
        $path = '';
        while ($request->hasFile('images.'.$i) && $i < 10) {
            $manager = new ImageManager(new Driver);
            $image = $manager->read($request->file('images.'.$i));
            $temp_file = sys_get_temp_dir().'/thumbnail';
            $image->scaleDown(1200, 1200)->toJpeg(90)->save($request->file('images.'.$i));
            $image->scaleDown(350, 350)->toJpeg(90)->save($temp_file);

            $paththumb = Storage::disk('images')->put('product/thumbnail/', new File($temp_file));
            $pathfull = Storage::disk('images')->put('product/', $request->file('images.'.$i));

            ProductGallery::create([
                'product_id' => $product->id,
                'src' => $pathfull,
                'thumbnail' => $paththumb,
            ]);

            $i++;
        }

        if ($request->input('update') == 'image' && $request->has('deleteImage')) {
            $fsname = substr($request->input('deleteImage'), strlen('/images/'));
            $image = ProductGallery::firstWhere('src', $fsname);
            if ($image) {
                $image->delete();
                // Storage::disk('images')->delete($request->input('deleteImage'));
            }
        }

        return Inertia::render('dashboard/products/edit', ['status' => 'success', 'imgpath' => $path, ...$tag]);
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
