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
}
