<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReceiptResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'receipt_id' => $this->id,
            'file_path'  => $this->file_path,
            'payment'    => [
                'amount'         => $this->payment->amount,
                'transaction_id' => $this->payment->transaction_id,
                'method'         => $this->payment->method,
                'currency'       => $this->payment->currency,
                'status'         => $this->payment->status,
            ],
            'visa_request' => [
                'visa_type'          => $this->payment->visaRequest->visaType->name ?? null,
                'origin_country'     => $this->payment->visaRequest->originCountry->name ?? null,
                'destination_country' => $this->payment->visaRequest->destinationCountry->name ?? null,
            ],
            'user' => [
                'name'  => $this->payment->visaRequest->user->name ?? null,
                'email' => $this->payment->visaRequest->user->email ?? null,
            ],
        ];
    }
}