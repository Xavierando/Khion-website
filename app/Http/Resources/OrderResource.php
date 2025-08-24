<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'order',
            'id' => $this->id,
            'status' => $this->status,
            'total' => $this->total,
            'stripe_checkout_id' => $this->stripe_checkout_id,
            'user' => new UserResource(User::find($this->user_id)),
            'items' => CartItemResource::collection($this->order_items),
            'statusOptions' => $this->statusOptions(),
        ];
    }
}
