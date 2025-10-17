<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Http\File;

class ProductGallery extends Model
{
    /** @use HasFactory<\Database\Factories\ProductGalleryFactory> */
    protected $guarded = [];

    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($image) {
            Storage::disk('images')->delete($image->src);
            Storage::disk('images')->delete($image->thumbnail);
        });
    }

    public function tags(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function generateStoragePathFromFile($sourceImage)
    {
        $manager = new ImageManager(new Driver);
        $image = $manager->read($sourceImage);
        $temp_file = sys_get_temp_dir() . '/thumbnail';
        $image->scaleDown(1200, 1200)->toJpeg(90)->save($sourceImage);
        $image->scaleDown(350, 350)->toJpeg(90)->save($temp_file);

        $this->thumbnail = Storage::disk('images')->put('product/thumbnail/', new File($temp_file));
        $this->src = Storage::disk('images')->put('product/', $sourceImage);
    }
}
