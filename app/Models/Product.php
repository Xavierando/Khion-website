<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Cashier\Cashier;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $casts = [
        'configuration' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($product) {
            $product->productGallery->map(fn ($v) => $v->delete());
        });
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)->orderBy('tag');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function productGallery(): HasMany
    {
        return $this->hasMany(ProductGallery::class);
    }

    public function updateAvailableQuantity()
    {
        $this->available_quantity = $this->quantity - CartItem::where('product_id', $this->id)->sum('quantity');

        return $this->save();
    }

    public function defaultImage()
    {
        if ($this->productGallery()->where('default', true)->count() > 0) {
            return $this->productGallery()->where('default', true)->first();
        }

        return $this->productGallery()->first();
    }

    public function genereteStripeID()
    {
        $stripe = Cashier::stripe();
        $product = $stripe->products->create(
            [
                'name' => $this->name,
                'description' => $this->description,
            ]
        );
        $this->stripe_id = $product['id'];

        $this->updateStripePrice();

        return $product['id'];
    }

    public function updateStripeInfo()
    {
        Cashier::stripe()->products->update(
            $this->stripe_id,
            [
                'name' => $this->name,
                'description' => $this->description,
            ]
        );

        return true;
    }

    public function updateStripePrice()
    {
        $stripe = Cashier::stripe();
        $price = $stripe->prices->create([
            'currency' => 'eur',
            'unit_amount' => $this->base_price * 100,
            'product' => $this->stripe_id,
        ]);

        $stripe->products->update(
            $this->stripe_id,
            [
                'default_price' => $price['id'],
            ]
        );

        try {
            $stripe->prices->update($this->stripe_price_id, [
                'active' => 'false',
            ]);
        } catch (\Exception $e) {
        }

        $this->stripe_price_id = $price['id'];

        return $price['id'];
    }

    public function resetDefaultImage()
    {
        $this->productGallery()->update(['default' => false]);
    }
}
