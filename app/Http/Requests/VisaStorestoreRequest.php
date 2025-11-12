<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VisaStorestoreRequest extends FormRequest
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
            'country_dest_name' => 'required|string',
            'visa_type_name' => 'required|string',
            'user_id' => 'required|integer|exists:users,id'
        ];
    }


    public function messages()
    {
        return [
            'country_dest_name.required' => 'Le nom du pays de destination est requis.',
            'visa_type_name.required' => 'Le nom du type de visa est requis.',
            'user_id.required' => 'L\'identifiant de l\'utilisateur est requis.',
            'user_id.integer' => 'L\'identifiant de l\'utilisateur doit être un entier.',
            'user_id.exists' => 'L\'utilisateur spécifié n\'existe pas.',
        ];
    }
}
