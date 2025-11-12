<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VisaStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'country_name' => 'required|string',
            'visa_type_name' => 'required|string',
            'price_base' => 'required|numeric',
            'price_per_child' => 'nullable|numeric',
            'processing_duration_min' => 'required|integer',
            'processing_duration_max' => 'required|integer',
            'status_mat' => 'required|in:single,married,divorced,widowed',
            'age' => 'required|integer',
            'documents' => 'required|array',
            'documents.*' => 'string|max:255',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'country_name.required' => 'Le nom du pays est requis.',
            'visa_type_name.required' => 'Le nom du type de visa est requis.',
            'price_base.required' => 'Le prix de base est requis.',
            'price_base.numeric' => 'Le prix de base doit être un nombre.',
            'price_per_child.numeric' => 'Le prix par enfant doit être un nombre.',
            'processing_duration_min.required' => 'La durée de traitement minimale est requise.',
            'processing_duration_min.integer' => 'La durée de traitement minimale doit être un entier.',
            'processing_duration_max.required' => 'La durée de traitement maximale est requise.',
            'processing_duration_max.integer' => 'La durée de traitement maximale doit être un entier.',
            'status_mat.in' => 'Le statut matrimonial doit être : single, married, divorced, widowed.',
            'age.integer' => 'L\'âge doit être un entier.',
            'documents.required' => 'La liste des documents est requise.',
            'documents.array' => 'La liste des documents doit être un tableau.',
            'documents.*.string' => 'Chaque document doit être une chaîne de caractères.',
            'documents.*.max' => 'Chaque document ne doit pas dépasser 255 caractères.',
        ];
    }
}