<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class StatusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'hash' => $this->hash,
            'body' => $this->body,
            'published' => $this->published,
            'user' => new UserResource($this->whenLoaded('user')),
            'images' => $this->getFirstMediaUrl('images'),
        ];
    }
}
