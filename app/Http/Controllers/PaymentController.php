<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\VisaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Afficher la liste des paiements.
     */
    public function index()
    {
        $payments = Payment::with('visaRequest')->latest()->paginate(10);
        return view('payments.index', compact('payments'));
    }

    /**
     * Afficher le formulaire de création d'un nouveau paiement.
     */
    public function create()
    {
        $visaRequests = VisaRequest::all();
        return view('payments.create', compact('visaRequests'));
    }

    /**
     * Enregistrer un nouveau paiement.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'visa_request_id' => 'required|exists:visa_requests,id',
            'amount' => 'required|numeric|min:0',
            'transaction_id' => 'required|string|unique:payments,transaction_id',
            'method' => 'required|string',
            'currency' => 'required|string|size:3',
            'status' => 'required|in:pending,paid,failed,cancelled',
            'meta' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $payment = Payment::create($validator->validated());

        return redirect()->route('payments.index')
            ->with('success', 'Paiement créé avec succès.');
    }

    /**
     * Afficher les détails d'un paiement spécifique.
     */
    public function show(Payment $payment)
    {
        $payment->load('visaRequest');
        return view('payments.show', compact('payment'));
    }

    /**
     * Afficher le formulaire d'édition d'un paiement.
     */
    public function edit(Payment $payment)
    {
        $visaRequests = VisaRequest::all();
        return view('payments.edit', compact('payment', 'visaRequests'));
    }

    /**
     * Mettre à jour un paiement existant.
     */
    public function update(Request $request, Payment $payment)
    {
        $validator = Validator::make($request->all(), [
            'visa_request_id' => 'required|exists:visa_requests,id',
            'amount' => 'required|numeric|min:0',
            'transaction_id' => 'required|string|unique:payments,transaction_id,' . $payment->id,
            'method' => 'required|string',
            'currency' => 'required|string|size:3',
            'status' => 'required|in:pending,paid,failed,cancelled',
            'meta' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $payment->update($validator->validated());

        return redirect()->route('payments.index')
            ->with('success', 'Paiement mis à jour avec succès.');
    }

    /**
     * Supprimer un paiement.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('payments.index')
            ->with('success', 'Paiement supprimé avec succès.');
    }
}