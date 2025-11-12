<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VisaRequestStoreRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'visa_type_name' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'country_dest_name' => 'required|string|max:255',
            'status' => 'nullable|string|in:pending,approved,rejected'
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
            'user_id.required' => 'L\'utilisateur est requis.',
            'user_id.exists' => 'L\'utilisateur doit exister.',
            'visa_type_name.required' => 'Le type de visa est requis.',
            'visa_type_name.exists' => 'Le type de visa doit exister.',
            'nationality.required' => 'Le pays d\'origine est requis.',
            'nationality.exists' => 'Le pays d\'origine doit exister.',
            'country_dest_name.required' => 'Le pays de destination est requis.',
            'country_dest_name.exists' => 'Le pays destination doit exister.',
            'status.required' => 'Le statut de la demande est requis.',
            'status.string' => 'Le statut doit être une chaîne de caractères.',
            'status.in' => 'Le statut de la demande doit être l\'un des suivants : pending, approved, rejected.'
        ];
    }
}