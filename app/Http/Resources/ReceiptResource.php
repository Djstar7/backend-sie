<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReceiptResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'file_path'  => $this->file_path,
            'payment'    => [
                'amount'         => $this->payment->amount,
                'transaction_id' => $this->payment->transaction_id,
                'method'         => $this->payment->method,
                'currency'       => $this->payment->currency,
                'status'         => $this->payment->status,
                'id' => $this->payment->id
            ],
            'visa_request' => [
                'visa_type_name'          => $this->payment->visaRequest->visaType->name ?? null,
                'country_origin_name'     => $this->payment->visaRequest->originCountry->name ?? null,
                'country_dest_name' => $this->payment->visaRequest->destinationCountry->name ?? null,
            ],
            'user' => [
                'name'  => $this->payment->visaRequest->user->name ?? null,
                'email' => $this->payment->visaRequest->user->email ?? null,
            ],
            'profil' => [
                'first_name' => $this->payment->visaRequest->user->profil->first_name ?? null,
                'last_name'  => $this->payment->visaRequest->user->profil->last_name ?? null,
                'phone'      => $this->payment->visaRequest->user->profil->phone ?? null,
            ],

            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
