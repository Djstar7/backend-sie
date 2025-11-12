<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomByAdminRequest extends FormRequest
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
            'phone' => 'required|string|max:20',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'required|date|before:today',
            'place_of_birth' => 'required|string|max:255',
            'status_mat' => 'required|in:single,married,divorced,widowed',
            'nationality' => 'required|string',
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
            'name.required'=> 'LE nom est requis.',
            'name.string'=> 'Le nom doit etre une chanie de caracteres',
            'name.max'=> 'Le nom doit avaoir maximum 255 caracterea',

            'email.required' => 'L\'email est requis.',
            'email.email' => 'L\'email doit être une adresse valide.',

            'phone.string' => 'Le téléphone doit être une chaîne de caractères.',
            'phone.max' => 'Le téléphone ne doit pas dépasser 20 caractères.',

            'first_name.required' => 'Le prénom est requis.',
            'first_name.string' => 'Le prénom doit être une chaîne de caractères.',
            'first_name.max' => 'Le prénom ne doit pas dépasser 255 caractères.',

            'last_name.required' => 'Le nom est requis.',
            'last_name.string' => 'Le nom doit être une chaîne de caractères.',
            'last_name.max' => 'Le nom ne doit pas dépasser 255 caractères.',

            'gender.required' => 'Le genre est requis.',
            'gender.in' => 'Le genre doit être soit \"male\" soit \"female\".',

            'date_of_birth.required' => 'La date de naissance est requise.',
            'date_of_birth.date' => 'La date de naissance doit être une date valide.',
            'date_of_birth.before' => 'La date de naissance doit être antérieure à aujourd\'hui.',

            'place_of_birth.required' => 'Le lieu de naissance est requis.',
            'place_of_birth.string' => 'Le lieu de naissance doit être une chaîne de caractères.',
            'place_of_birth.max' => 'Le lieu de naissance ne doit pas dépasser 255 caractères.',

            'status_mat.required' => 'Le statut matrimonial est requis.',
            'status_mat.in' => 'Le statut doit être soit célibataire, marié, divorcé ou veuf.',

            'nationality.required' => 'La nationalité est requiise.',
            'nationality.string'=> 'La nationalite dois etre une chaine de caractere',
        ];
    }
}