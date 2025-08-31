<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

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
}
