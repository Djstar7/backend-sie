<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BackupStoreRequest extends FormRequest
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
            'backup_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // max 5 Mo
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
            'backup_file.required' => 'Le fichier de backup est obligatoire.',
            'backup_file.file' => 'Le fichier doit être valide.',
            'backup_file.mimes' => 'Le fichier doit être au format PDF, JPG, JPEG ou PNG.',
            'backup_file.max' => 'Le fichier ne doit pas dépasser 5 Mo.',
        ];
    }
}