<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VisaResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'country' => $this->country?->name,
            'visaType' => $this->visaType?->name,
            'price_base' => $this->price_base,
            'price_per_child' => $this->price_per_child,
            'processing_duration_min' => $this->processing_duration_min,
            'processing_duration_max' => $this->processing_duration_max,
            'documents' => RequiredDocumentResource::collection($this->whenLoaded('requiredDocuments')),
        ];
    }
}