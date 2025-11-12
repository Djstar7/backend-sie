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
            'date' => $this->date,
            'status' => $this->status,
            'notes' => $this->notes,
            'created_at' => $this->created_at?->format('Y-m-d H:i'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i'),
            'visa_request' => $this->whenLoaded('visaRequest', function () {
                return [
                    'id' => $this->visaRequest->id,
                    'type' => $this->visaRequest->type ?? null,
                    'country' => $this->visaRequest->country->countryVisaTypes ?? null,
                ];
            }),
        ];
    }
}
