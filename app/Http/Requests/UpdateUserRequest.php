<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'email' => 'required|email',
            'password' => 'nullable|string|min:6',
            'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048'
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
            'name.required' => 'Le nom est requis.',
            'email.required' => 'L\'email est requis.',
            'email.email' => 'L\'adresse email n\'est pas valide.',
            'image.mimes' => 'Le format de l\'image doit être jpeg, png, jpg ou gif.',
            'image.max' => 'La taille maximale de l\'image ne doit pas dépasser 2Mo.'
        ];
    }
}
