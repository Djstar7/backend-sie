<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'visa_request_id' => 'required|exists:visa_requests,id',
            'content' => 'required|string',
            'status' => 'nullable|in:sent,received,read,archived',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'L’utilisateur est obligatoire.',
            'user_id.exists' => 'L’utilisateur spécifié n’existe pas.',
            'visa_request_id.required' => 'La demande de visa est obligatoire.',
            'visa_request_id.exists' => 'Cette demande de visa n’existe pas.',
            'content.required' => 'Le contenu est obligatoire.',
            'content.string' => 'Le contenu doit être une chaîne de caractères.',
            'status.in' => 'Le statut doit être : sent, received, read ou archived.',
        ];
    }
}