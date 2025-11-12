<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RequiredDocumentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status_mat' => $this->status_mat,
            'age_min' => $this->age_min,
            'age_max' => $this->age_max,
        ];
    }
}