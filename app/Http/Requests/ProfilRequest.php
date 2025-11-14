<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfilRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name'     => 'required|string|max:255',
            'last_name'      => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'gender'         => 'required|in:male,female',
            'date_of_birth'  => 'required|date|before:today',
            'place_of_birth' => 'required|string|max:255',
            'status_mat'     => 'required|in:single,married,divorced,widowed',
            'nationality'    => 'required|string|exists:countrys,name',
            'user_id'    => 'required|string|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required'    => 'Le prénom est obligatoire.',
            'last_name.required'     => 'Le nom de famille est obligatoire.',
            'phone.required'         => 'Le numéro de téléphone est obligatoire',
            'gender.required'        => 'Le genre est obligatoire.',
            'date_of_birth.required' => 'La date de naissance est obligatoire.',
            'place_of_birth.required' => 'Le lieu de naissance est obligatoire.',
            'status_mat.required'    => 'Le statut matrimonial est obligatoire.',
            'status_mat.in'          => 'Le statut matrimonial doit être l\'un des suivants : celibataire, mariee, divorcer, Veuve/Veuf.',
            'nationality.required'     => 'La nationalité est obligatoire.',
            'nationality.exists'       => 'La nationalité spécifiée est invalide.',
            'user_id.required'     => 'L\'identifiant utilisateur est obligatoire.',
            'user_id.exists'       => 'L\'utilisateur spécifié est invalide.',
        ];
    }
}
