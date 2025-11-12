<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
        // Si c'est une mise à jour, certains champs peuvent être nullable
        $paymentId = $this->route('id'); // récupère l'id si update

        $rules = [
            'visa_request_id' => $paymentId ? 'sometimes|integer|exists:visa_requests,id' : 'required|integer|exists:visa_requests,id',
            'amount'         => $paymentId ? 'nullable|numeric|min:0' : 'required|numeric|min:0',
            'transaction_id' => $paymentId ? "sometimes|string|unique:payments,transaction_id,{$paymentId}" : 'required|string|unique:payments,transaction_id',
            'method'         => $paymentId ? 'nullable|string|max:50' : 'required|string|max:50',
            'currency'       => $paymentId ? 'nullable|string|size:3' : 'required|string|size:3',
            'status'         => $paymentId ? 'nullable|string|in:pending,success,failed' : 'required|string|in:pending,success,failed',
            'meta'           => 'nullable|json',
        ];

        return $rules;
    }

    /**
     * Messages personnalisés pour chaque champ
     */
    public function messages(): array
    {
        return [
            'visa_request_id.required' => 'La demande de visa est obligatoire.',
            'visa_request_id.exists'   => 'Cette demande de visa n’existe pas.',
            'amount.required'          => 'Le montant est obligatoire.',
            'amount.numeric'           => 'Le montant doit être un nombre.',
            'amount.min'               => 'Le montant doit être supérieur ou égal à 0.',
            'transaction_id.required'  => 'L’identifiant de transaction est obligatoire.',
            'transaction_id.unique'    => 'Cette transaction existe déjà.',
            'method.required'          => 'La méthode de paiement est obligatoire.',
            'method.max'               => 'La méthode ne doit pas dépasser 50 caractères.',
            'currency.required'        => 'La devise est obligatoire.',
            'currency.size'            => 'La devise doit contenir 3 caractères.',
            'status.in'                => 'Le statut doit être : pending, success ou failed.',
            'meta.json'                => 'Le champ meta doit être un JSON valide.',
        ];
    }
}