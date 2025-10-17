<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\CartResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductGallery;
use App\Models\Tag;
use App\Traits\ApiResponses;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

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
        $product->available_quantity = $request->input('quantity');
        $product->configuration = '{"options":[]}';
        $product->genereteStripeID();
        $product->save();
        $product->updateAvailableQuantity();

        $i = 0;
        while ($request->hasFile('images.' . $i) && $i < 10) {
            $manager = new ImageManager(new Driver);
            $image = $manager->read($request->file('images.' . $i));
            $temp_file = sys_get_temp_dir() . '/thumbnail';
            $image->scaleDown(1200, 1200)->toJpeg(90)->save($request->file('images.' . $i));
            $image->scaleDown(350, 350)->toJpeg(90)->save($temp_file);

            $paththumb = Storage::disk('images')->put('product/thumbnail/', new File($temp_file));
            $pathfull = Storage::disk('images')->put('product/', $request->file('images.' . $i));

            ProductGallery::create([
                'product_id' => $product->id,
                'src' => $pathfull,
                'thumbnail' => $paththumb,
            ]);

            $i++;
        }

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


        if ($request->has('images')) {
            foreach ($request->input('images') as $indexImage => $clientImage) {
                if (isset($clientImage['blob'])) {
                    $manager = new ImageManager(new Driver);
                    $image = $manager->read($clientImage['blob']);
                    $temp_file = sys_get_temp_dir() . '/thumbnail';
                    $image->scaleDown(1200, 1200)->toJpeg(90)->save($request->file('images.' . $indexImage . '.blob'));
                    $image->scaleDown(350, 350)->toJpeg(90)->save($temp_file);

                    $paththumb = Storage::disk('images')->put('product/thumbnail/', new File($temp_file));
                    $pathfull = Storage::disk('images')->put('product/', $request->file('images.' . $indexImage . '.blob'));

                    ProductGallery::create([
                        'product_id' => $product->id,
                        'src' => $pathfull,
                        'thumbnail' => $paththumb,
                    ]);
                }
                if (isset($clientImage['delete'])) {
                    $fsname = substr($clientImage['src'], strlen('/images/'));
                    $image = ProductGallery::firstWhere('src', $fsname);
                    if ($image) {
                        $image->delete();
                        // Storage::disk('images')->delete($request->input('deleteImage'));
                    }
                }
            }
        }



        $product->save();

        return $this->ok('success', [
            'product' => new ProductResource($product)
        ]);
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
        return $this->ok('deleted');
    }
}
