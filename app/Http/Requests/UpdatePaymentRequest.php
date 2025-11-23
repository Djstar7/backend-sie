<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        // Tu peux adapter si tu veux vérifier un rôle, sinon true
        return true;
    }

    /**
     * Règles de validation pour la requête.
     */
    public function rules(): array
    {
        return [
            'status' =>  'required|in:pending,delete,failed,processing,completed,canceled,expired'
        ];
    }

    /**
     * Messages personnalisés pour chaque champ
     */
    public function messages(): array
    {
        return [
            'status.required' => 'Le statut est requis',
            'status.in' => 'Le statut doit être : en cours, supprimer, echouer, traitement en cours, coompleter, annuler ou expirer.',
        ];
    }
}
