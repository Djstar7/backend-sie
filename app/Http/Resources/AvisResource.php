<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AvisResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'user_id'  => $this->user_id,
            'content'  => $this->content,
            'rating'   => $this->rating,
            'name'     => $this->user->name ?? null,

            // Optionnel: infos de l’utilisateur (si tu veux)
            // 'user' => new UserResource($this->whenLoaded('user')),

            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
