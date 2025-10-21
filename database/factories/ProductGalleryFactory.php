<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

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

        $manager = new ImageManager(new Driver);
        $image = $manager->read(new File(public_path('images/snowboard1.jpg')));
        $temp_file_thumb = sys_get_temp_dir().'/thumbnail';
        $temp_file = sys_get_temp_dir().'/img';
        $image->scaleDown(1200, 1200)->toJpeg(90)->save($temp_file);
        $image->scaleDown(350, 350)->toJpeg(90)->save($temp_file_thumb);

        $paththumb = Storage::disk('images')->put('product/thumbnail/', new File($temp_file_thumb));
        $path = Storage::disk('images')->put('product/', new File($temp_file));

        /*

                $paththumb = Storage::disk('images')->put('product/thumbnail', new File(public_path('images/snowboard1.png')));
                $path = Storage::disk('images')->put('product/', new File(public_path('images/snowboard1.png')));
        */
        return [
            'thumbnail' => $paththumb,
            'src' => $path,
            'default' => false,
        ];
    }
}
