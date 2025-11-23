<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VisaRequestUpdateRequest extends FormRequest
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
            'status' => 'sometimes|string|in:approved,processing,rejected'
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
            'status.required' => 'Le statut de la demande est requis.',
            'status.string' => 'Le statut doit être une chaîne de caractères.',
            'status.in' => 'Le statut de la demande doit être l\'un des suivants :  approved, procesing, rejected.'
        ];
    }
}
