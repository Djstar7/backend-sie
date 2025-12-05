<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAvisRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // gère selon ton système de permissions si besoin
    }

    public function rules(): array
    {
        return [
            'user_id' => ['sometimes', 'string', 'exists:users,id'],
            'content' => ['sometimes', 'string', 'max:5000'],
            'rating'  => ['sometimes', 'integer', 'min:1', 'max:5'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.exists'   => 'L’utilisateur spécifié n’existe pas.',
            'content.string'   => 'Le contenu doit être une chaîne de caractères.',
            'rating.min'       => 'La note minimale est 1.',
            'rating.max'       => 'La note maximale est 5.',
        ];
    }
}
