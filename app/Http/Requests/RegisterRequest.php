<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'password' => [
                'required',
                'string',
                'min:8',
                function ($attribute, $value, $fail) {
                    $conditionsMet = 0;
                    if (preg_match('/[a-z]/', $value)) $conditionsMet++;
                    if (preg_match('/[A-Z]/', $value)) $conditionsMet++;
                    if (preg_match('/\d/', $value)) $conditionsMet++;
                    if (preg_match('/[^A-Za-z0-9]/', $value)) $conditionsMet++;

                    if ($conditionsMet < 3) {
                        $fail('Le mot de passe doit contenir au moins 3 des 4 criteres : minuscule, majuscule, chiffre, caractere special.');
                    }
                },
            ],
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
            'password.required' => 'Le mot de passe est requis.',
        ];
    }
}