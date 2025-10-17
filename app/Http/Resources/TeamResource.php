<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'team',
            'name' => $this->name,
            'bio' => $this->bio,
            'role' => $this->role,
            'url' => '/images/'.$this->imageUrl,
        ];
    }
}
