<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VisaUpdateRequest extends FormRequest
{
    /**
     * Autoriser ou non la requête
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation
     */
    public function rules(): array
    {
        return [
            'price_base' => 'nullable|numeric',
            'price_per_child' => 'nullable|numeric',
            'processing_duration_min' => 'nullable|integer',
            'processing_duration_max' => 'nullable|integer',
        ];
    }

    /**
     * Messages d'erreur personnalisés
     */
    public function messages(): array
    {
        return [
            'price_base.numeric' => 'Le prix de base doit être un nombre.',
            'price_per_child.numeric' => 'Le prix par enfant doit être un nombre.',
            'processing_duration_min.integer' => 'La durée minimale de traitement doit être un entier.',
            'processing_duration_max.integer' => 'La durée maximale de traitement doit être un entier.',
        ];
    }
}