<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppoitmentResource extends JsonResource
{
    /**
     * Transforme le rendez-vous pour l'API
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'visa_request_id' => $this->visa_request_id,
            'user_id' => $this->visaRequest?->user_id,
            'scheduled_at' => $this->scheduled_at,
            'status' => $this->status,
            'created_at' => $this->created_at?->format('Y-m-d H:i'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i'),
        ];
    }
}
