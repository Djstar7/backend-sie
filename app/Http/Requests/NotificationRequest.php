<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotificationRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'type' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'nullable|in:sent,delivered,failed',
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
            'user_id.required' => 'L’utilisateur est obligatoire.',
            'user_id.exists' => 'L’utilisateur spécifié n’existe pas.',
            'type.required' => 'Le type de notification est obligatoire.',
            'type.string' => 'Le type doit être une chaîne de caractères.',
            'type.max' => 'Le type ne doit pas dépasser 255 caractères.',
            'content.required' => 'Le contenu est obligatoire.',
            'content.string' => 'Le contenu doit être une chaîne de caractères.',
            'status.in' => 'Le statut doit être : sent, delivered ou failed.',
        ];
    }
}
