<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'product',
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'base_price' => $this->base_price,
            'imageUrl' => '/images/'.$this->imageUrl,
            'short' => str($this->description)->words(20, '...'),
            'link' => '/products/'.$this->id,
            'configuration' => $this->configuration,
        ];
    }
}
