<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppoitmentStoreAndUpdateRequest extends FormRequest
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
            'visa_request_id' => 'required|exists:visa_requests,id',
            'scheduled_at'    => 'required|date|after:now',
            'status'          => 'required|in:pending,rescheduled,canceled,completed',
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
            'visa_request_id.required' => 'Le rendez-vous doit être lié à une demande de visa.',
            'visa_request_id.exists' => 'La demande de visa doit exister.',
            'scheduled_at.required' => 'La date du rendez-vous est requise.',
            'scheduled_at.date' => 'La date du rendez-vous doit être une date valide.',
            'scheduled_at.after' => 'La date du rendez-vous doit être dans le futur.',
            'status.required' => 'Le statut du rendez-vous est requis.',
            'status.in' => 'Le statut du rendez-vous doit être l\'un des suivants : pending, rescheduled, canceled, completed.',
        ];
    }
}
