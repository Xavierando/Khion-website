<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductGalleryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'thumb' => '/images/'.$this->fsname,
            'src' => '/images/'.$this->fsname,
            'caption' => '/images/'.$this->description,
        ];
    }
}
