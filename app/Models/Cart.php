<?php

namespace App\Models;

use App\Enums\CartStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    /** @use HasFactory<\Database\Factories\CartFactory> */
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'status' => CartStatus::class,
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cart_items(): HasMany
    {
        return $this->hasMany(Cart_item::class);
    }
}
