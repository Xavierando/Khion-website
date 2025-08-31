<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductGallery>
 */
class ProductGalleryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $paththumb = Storage::disk('images')->put('product/thumbnail', new File(public_path('images/snowboard1.png')));
        $path = Storage::disk('images')->put('product/', new File(public_path('images/snowboard1.png')));

        return [
            'thumbnail' => $paththumb,
            'src' => $path,
        ];
    }
}
