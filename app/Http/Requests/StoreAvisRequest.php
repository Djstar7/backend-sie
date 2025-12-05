<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAvisRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // autorisation à gérer selon ton système
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'string', 'exists:users,id'],
            'content' => ['required', 'string', 'max:5000'],
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'L’ID de l’utilisateur est obligatoire.',
            'user_id.exists'   => 'L’utilisateur spécifié n’existe pas.',
            'content.required' => 'Le contenu du message est obligatoire.',
            'rating.required'  => 'La note est obligatoire.',
            'rating.min'       => 'La note minimale est 1.',
            'rating.max'       => 'La note maximale est 5.',
        ];
    }
}
