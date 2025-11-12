<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CountryUpdateRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'iso_code' => 'required|string|min:2',
            'phone_code' => 'required|string|max:10',
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
            'name.required' => 'Le nom du pays est obligatoire.',
            'name.string' => 'Le nom du pays doit être une chaîne de caractères.',
            'name.max' => 'Le nom du pays ne doit pas dépasser 255 caractères.',
            'name.unique' => 'Ce nom de pays existe déjà.',

            'iso_code.required' => 'Le code ISO est obligatoire.',
            'iso_code.string' => 'Le code ISO doit être une chaîne de caractères.',
            'iso_code.min' => 'Le code ISO doit contenir minimum 2 caractères.',
            'iso_code.unique' => 'Ce code ISO existe déjà.',

            'phone_code.required' => 'L’indicatif téléphonique est obligatoire.',
            'phone_code.string' => 'L’indicatif téléphonique doit être une chaîne.',
            'phone_code.max' => 'L’indicatif téléphonique ne doit pas dépasser 10 caractères.',
        ];
    }
}