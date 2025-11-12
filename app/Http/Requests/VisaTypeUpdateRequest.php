<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VisaTypeUpdateRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:visa_types,name,' . $this->route('id'),
            'description' => 'nullable|string|max:1000',
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
            'name.required' => 'Le nom du type de visa est obligatoire.',
            'name.string' => 'Le nom du type de visa doit être une chaîne de caractères.',
            'name.max' => 'Le nom du type de visa ne doit pas dépasser 255 caractères.',
            'name.unique' => 'Ce type de visa existe déjà.',

            'description.string' => 'La description doit être une chaîne de caractères.',
            'description.max' => 'La description ne doit pas dépasser 1000 caractères.',
        ];
    }
}