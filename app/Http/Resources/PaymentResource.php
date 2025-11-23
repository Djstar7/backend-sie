<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'             => $this->id,
            'userName'   => $this->visaRequest->user->name,
            'userEmail'  => $this->visaRequest->user->email,
            'visa_request_id' => $this->visa_request_id,
            'amount'         => $this->amount,
            'transaction_id' => $this->transaction_id,
            'method'         => $this->method,
            'currency'       => $this->currency,
            'status'         => $this->status,
            'meta'           => $this->meta ?? null,
            'created_at'     => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at'     => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
