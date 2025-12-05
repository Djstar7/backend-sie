<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'email' => 'required|email|unique:users,email',
            'password' => 'nullable|string|min:6',
            'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'role' => 'nullable|in:agent,admin,custom',
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
            'email.unique' => 'Cet email est déjà utilisé.',
            'image.mimes' => 'L\'image doit être au format jpeg, png, jpg, gif ou svg.',
            'image.max' => 'La taille de l\'image ne doit pas dépasser 2Mo.',
            'role.required' => 'Le role est requis',
            'role.in' => 'Le role doit etre soit agent ou admin ou custom',
        ];
    }
}
