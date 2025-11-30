<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VisaRequestResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email
            ],
            'profil' => [
                'first_name' => $this->user->profil->first_name ?? null,
                'last_name' => $this->user->profil->last_name ?? null,
                'phone' => $this->user->profil->phone ?? null,
                'date_of_birth' => $this->user->profil?->date_of_birth ?? null,
                'place_of_birth' => $this->user->profil?->place_of_birth ?? null,
                'status_mat' => $this->user->profil?->status_mat ?? null,
            ],
            'visa_type_name' => $this->visaType->name ?? null,
            'visa_type_id' => $this->visaType->id ?? null,
            'visa_type_desc' => $this->visaType->description ?? null,
            'country_origin_name' => $this->originCountry->name ?? null,
            'country_dest_name' => $this->destinationCountry->name ?? null,
            'status' => $this->status,
            'created_at' => $this->created_at?->format('Y-m-d H:i'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i'),
        ];
    }
}
