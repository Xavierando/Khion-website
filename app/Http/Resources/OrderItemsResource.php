<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'order_item',
            'id' => $this->id,
            'price' => $this->price,
            'name' => $this->product->name,
            'configuration' => $this->configuration['options'],
            'createdAt' => $this->created_at,
        ];
    }
}
