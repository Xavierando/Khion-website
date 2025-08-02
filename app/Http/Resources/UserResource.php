<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'user',
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'isTeam' => $this->isTeam,
            'bio' => $this->bio,
            'role' => $this->role,
            'url' => $this->imageUrl,
        ];
    }
}
