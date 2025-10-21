<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ProductGallery extends Model
{
    /** @use HasFactory<\Database\Factories\ProductGalleryFactory> */
    protected $guarded = [];

    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($image) {
            Storage::delete($image->src);
            Storage::delete($image->thumbnail);
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function generateStoragePathForUpload($sourceImage)
    {
        $this->thumbnail = $this->generateStoragePathForThumbnail($sourceImage);
        $this->src = $this->generateStoragePathForBigImage($sourceImage);
    }

    public function generateStoragePathForBigImage($sourceImage)
    {
        return $this->generateStoragePath($sourceImage, 1200, 'product/');
    }

    public function generateStoragePathForThumbnail($sourceImage)
    {
        return $this->generateStoragePath($sourceImage, 350, 'product/thumbnail/');
    }

    public function generateStoragePath($sourceImage, $maxSize, $path)
    {
        $manager = new ImageManager(new Driver);
        $image = $manager->read($sourceImage);
        $temp_file = sys_get_temp_dir().'/tmpImage';
        $image->scaleDown($maxSize, $maxSize)->toJpeg(90)->save($temp_file);

        return Storage::put($path, new File($temp_file));
    }

    public function setAsDefault()
    {
        $this->product->resetDefaultImage();
        $this->default = true;
        $this->save();
    }
}
