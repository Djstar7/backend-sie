<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppoitmentUpdateByUserRequest extends FormRequest
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
            'status' => 'required|in:pending,rescheduled,canceled,completed',
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
            'status.required' => 'Le statut du rendez-vous est requis.',
            'status.in' => 'Le statut du rendez-vous doit être l\'un des suivants : pending, rescheduled, canceled, completed.',
        ];
    }
}
