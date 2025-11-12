<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentUpdateRequest extends FormRequest
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
            'visa_request_id' => 'required|exists:visa_requests,id',
            'name' => 'required|string|max:255',
            'document_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'is_validated' => 'nullable|boolean',
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
            'visa_request_id.required' => 'La demande de visa est obligatoire.',
            'visa_request_id.exists' => 'Cette demande de visa n’existe pas.',
            'name.required' => 'Le nom du document est obligatoire.',
            'name.string' => 'Le nom doit être une chaîne de caractères.',
            'name.max' => 'Le nom ne doit pas dépasser 255 caractères.',
            'document_file.required' => 'Le fichier du document est obligatoire.',
            'document_file.file' => 'Le fichier doit être valide.',
            'document_file.mimes' => 'Le fichier doit être au format PDF, JPG, JPEG ou PNG.',
            'document_file.max' => 'Le fichier ne doit pas dépasser 10 Mo.',
            'is_validated.boolean' => 'Le champ \"is_validated\" doit être vrai ou faux.',
        ];
    }
}