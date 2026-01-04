<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VisaUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Validation rules for updating a visa.
     */
    public function rules()
    {
        return [
            'country_name' => 'sometimes|string',
            'visa_type_name' => 'sometimes|string',
            'price_base' => 'sometimes|numeric',
            'price_per_child' => 'sometimes|numeric|nullable',
            'processing_duration_min' => 'sometimes|integer',
            'processing_duration_max' => 'sometimes|integer',

            'status_mat' => 'sometimes|array|min:1',
            'status_mat.*' => 'in:single,married,divorced,widowed',
            'min_age' => 'sometimes|integer',
            'max_age' => 'sometimes|integer',

            'documents' => 'sometimes|array',
            'documents.*' => 'string|max:255',
        ];
    }

    /**
     * Custom messages.
     */
    public function messages()
    {
        return [
            'country_name.string' => 'Le nom du pays doit être une chaîne de caractères.',
            'visa_type_name.string' => 'Le nom du type de visa doit être une chaîne de caractères.',
            'price_base.numeric' => 'Le prix de base doit être un nombre.',
            'price_per_child.numeric' => 'Le prix par enfant doit être un nombre.',
            'processing_duration_min.integer' => 'La durée minimale doit être un entier.',
            'processing_duration_max.integer' => 'La durée maximale doit être un entier.',
            'status_mat.array' => 'Le statut matrimonial doit être un tableau.',
            'status_mat.min' => 'Au moins un statut matrimonial doit être sélectionné.',
            'status_mat.*.in' => 'Le statut matrimonial doit être : single, married, divorced, widowed.',
            'min_age.integer' => 'L\'âge minimum doit être un entier.',
            'max_age.integer' => 'L\'âge maximum doit être un entier.',
            'documents.array' => 'La liste des documents doit être un tableau.',
            'documents.*.string' => 'Chaque document doit être une chaîne de caractères.',
            'documents.*.max' => 'Chaque document ne doit pas dépasser 255 caractères.',
        ];
    }
}
