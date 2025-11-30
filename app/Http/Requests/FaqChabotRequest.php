<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FaqChabotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'category' => 'nullable|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'question.required' => 'La question est requise.',
            'question.string' => 'La question doit être une chaîne de caractères.',
            'question.max' => 'La question ne doit pas dépasser 255 caractères.',
            'answer.required' => 'La réponse est requise.',
            'answer.string' => 'La réponse doit être une chaîne de caractères.',
            'category.string' => 'La catégorie doit être une chaîne de caractères.',
            'category.max' => 'La catégorie ne doit pas dépasser 100 caractères.',
        ];
    }
}
