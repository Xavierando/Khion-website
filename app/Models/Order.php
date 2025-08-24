<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'status' => OrderStatus::class,
    ];

    protected $statusRelation = [
        OrderStatus::pending->value => [OrderStatus::paid],
        OrderStatus::paid->value => [OrderStatus::production, OrderStatus::expedited],
        OrderStatus::production->value => [OrderStatus::paid, OrderStatus::expedited],
        OrderStatus::expedited->value => [],
    ];

    public function order_items(): HasMany
    {
        return $this->hasMany(Cart_item::class, 'cart_id', 'cart_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function statusOptions(): array
    {
        return $this->statusRelation[$this->status->value];
    }

    public function statusUpdate(OrderStatus $status)
    {
        Log::debug($this->statusOptions());
        if (array_find($this->statusOptions(), fn ($v) => $v == $status)) {
            $this->status = $status;
            $this->save();
        }

        return $this->status;
    }
}
