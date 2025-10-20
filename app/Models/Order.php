<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Cashier;

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
        return $this->hasMany(CartItem::class, 'cart_id', 'cart_id');
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
        if (array_find($this->statusOptions(), fn($v) => $v == $status)) {
            $this->status = $status;
            $this->save();
        }

        return $this->status;
    }

    public function checkoutUrl()
    {
        if ($this->stripe_checkout_id) {
            $checkoutSession = Cashier::stripe()->checkout->sessions->retrieve($this->stripe_checkout_id);
            if ($checkoutSession->status === 'open') {
                return $checkoutSession->url;
            }
            if ($checkoutSession->status === 'expired' && $this->status == OrderStatus::pending) {
                $checkoutSession = $this->createCheckoutSession();
                $this->stripe_checkout_id = $checkoutSession->id;
                $this->save();
                return $checkoutSession->url;
            }
        }

        return '';
    }

    public function createCheckoutSession()
    {
        $checkout = $this->order_items->mapWithKeys(function ($i) {
            return [
                $i['product']['stripe_price_id'] => intval($i['quantity']),
            ];
        })->toArray();
        $checkoutSession = $this->user->checkout($checkout, [
            'success_url' => env('APP_URL') . '/checkout/' . $this->id,
            'cancel_url' =>  env('APP_URL') . '/checkout/' . $this->id,
            'metadata' => ['order_id' => $this->id],
        ]);

        return $checkoutSession;
    }

    public function checkPayementStatus()
    {
        if ($this->status === OrderStatus::pending) {
            $checkoutSession =  $this->checkoutSession();
            if ($checkoutSession) {
                if ($checkoutSession->payment_status === 'paid') {
                    $this->statusUpdate(OrderStatus::paid);
                    $this->save();
                }
            }
        }
    }

    public function checkoutSession()
    {
        if (
            $this->stripe_checkout_id
        ) {
            return Cashier::stripe()->checkout->sessions->retrieve($this->stripe_checkout_id);
        } else {
            return false;
        }
    }

    public function checkoutSessionGetOrCreate()
    {
        $checkoutSession = $this->checkoutSession();

        if ($checkoutSession && $checkoutSession->status === 'expired') {
            return $this->createCheckoutSession();
        }

        return $checkoutSession;
    }
}
