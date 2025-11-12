<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BackupResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'backup_id' => $this->id,
            'file_path' => $this->file_path,
            'visa_request' => [
                'id' => $this->visaRequest->id,
                'status' => $this->visaRequest->status,
                'visa_type' => $this->visaRequest->visaType->name ?? null,
                'origin_country' => $this->visaRequest->originCountry->name ?? null,
                'destination_country' => $this->visaRequest->destinationCountry->name ?? null,
                'documents' => $this->visaRequest->documents->map(fn($doc) => [
                    'name' => $doc->name,
                    'status_mat' => $doc->status_mat,
                ]),
            ],
            'user' => [
                'name' => $this->visaRequest->user->name ?? null,
                'email' => $this->visaRequest->user->email ?? null,
                'profil' => [
                    'first_name' => $this->visaRequest->user->first_name ?? null,
                    'last_name' => $this->visaRequest->user->last_name ?? null,
                    'phone' => $this->visaRequest->user->phone ?? null,
                    'gender' => $this->visaRequest->user->gender ?? null,
                    'date_of_birth' => $this->visaRequest->user->date_of_birth ?? null,
                    'place_of_birth' => $this->visaRequest->user->place_of_birth ?? null,
                    'status_mat' => $this->visaRequest->user->status_mat ?? null,
                    'nationality' => $this->visaRequest->user->country->name ?? null,
                ],
            ],
            'payments' => $this->visaRequest->payments->map(fn($payment) => [
                'amount' => $payment->amount,
                'transaction_id' => $payment->transaction_id,
                'method' => $payment->method,
                'currency' => $payment->currency,
                'status' => $payment->status,
                'receipts' => $payment->receipts->pluck('file_path'),
            ]),
        ];
    }
}