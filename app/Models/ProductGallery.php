<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductGallery extends Model
{
    /** @use HasFactory<\Database\Factories\ProductGalleryFactory> */
    protected $guarded = [];
    
    use HasFactory;

        public function tags(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
