<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReceiptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'payment_id'   => 'required|exists:payments,id',
            'receipt_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // max 5 Mo
        ];

        if ($this->isMethod('PATCH') || $this->isMethod('PUT')) {
            // Si update, le fichier peut être nullable
            $rules['receipt_file'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'payment_id.required'   => 'Le paiement associé est obligatoire.',
            'payment_id.exists'     => 'Le paiement spécifié n’existe pas.',
            'receipt_file.required' => 'Le fichier du reçu est obligatoire.',
            'receipt_file.file'     => 'Le fichier doit être valide.',
            'receipt_file.mimes'    => 'Le fichier doit être au format PDF, JPG, JPEG ou PNG.',
            'receipt_file.max'      => 'Le fichier ne doit pas dépasser 5 Mo.',
        ];
    }
}
