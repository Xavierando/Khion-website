<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductGallery;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductGalleryController extends Controller
{
    use ApiResponses;


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'file' => 'required|file',
        ]);

        $image = new ProductGallery();
        $image->product_id = $product->id;
        Log::debug("message");
        $image->generateStoragePathFromFile($request->File('file'));
        $image->save();

        return $this->ok("success");
    }

    /**
     * Update resource in storage.
     */
    public function update(Request $request, ProductGallery $productGallery)
    {

        $productGallery->product->resetDefaultImage;
        $productGallery->default = true;
        $productGallery->save();

        return $this->ok("success");
    }

    /**
     * Delete resource in storage.
     */
    public function delete(Request $request, ProductGallery $productGallery)
    {
        $productGallery->delete();
        return $this->ok("success");
    }
}
