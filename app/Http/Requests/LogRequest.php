<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // autoriser toutes les requêtes, à adapter si besoin
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'action' => 'required|string|max:255',
            'description' => 'nullable|string',
            'adress' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'L’utilisateur est obligatoire.',
            'user_id.exists' => 'L’utilisateur spécifié n’existe pas.',
            'action.required' => 'L’action est obligatoire.',
            'action.string' => 'L’action doit être une chaîne de caractères.',
            'action.max' => 'L’action ne doit pas dépasser 255 caractères.',
            'description.string' => 'La description doit être une chaîne de caractères.',
            'adress.string' => 'L’adresse doit être une chaîne de caractères.',
            'adress.max' => 'L’adresse ne doit pas dépasser 255 caractères.',
        ];
    }
}