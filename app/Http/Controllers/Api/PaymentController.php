<?php

namespace App\Http\Controllers\Api;

use App\Events\UserActionEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Payment;
use App\Http\Resources\PaymentResource;
use App\Models\VisaRequest;
use Illuminate\Support\Facades\Log;
use App\Services\NotchPayService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Str;

class PaymentController extends Controller
{
    protected $notchPayService;

    public function __construct(NotchPayService $notchPayService)
    {
        $this->notchPayService = $notchPayService;
    }

    // Liste des paiements
    public function index()
    {
        try {
            $payments = Payment::all()->sortByDesc('created_at');

            if ($payments->isEmpty()) {
                return response()->json(['message' => 'Aucun paiement trouvé'], 404);
            }

            return response()->json(['data' => PaymentResource::collection($payments)]);
        } catch (\Exception $e) {
            Log::error('Erreur récupération des paiements : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur lors de la récupération des paiements'], 500);
        }
    }

    // Créer un nouveau paiement
    public function store(PaymentRequest $request)
    {
        try {
            $validated = $request->validated();
            $validated['transaction_id'] = Str::random(36);
            $validated['meta'] = [
                'city' => 'Mbouda',
                'region' => 'West',
                'BP' => '62 Mbouda'
            ];
            $payment = Payment::create($validated);
            $visaRequest = VisaRequest::find($validated['visa_request_id']);
            $visaRequest->update(['status' => 'processing']);
            UserActionEvent::dispatch(Auth::user(), [
                "type" => "Paiement",
                "message" => "Paiement effectuer avec succes votree demande est desormais sur en traiement aau prese de notre services agent et egalement vos avez la possibiliter de les ecrire directement concernant votre demande",
                "link" => "/custom/visarequest/show/{$validated['visa_request_id']}"
            ]);
            return response()->json([
                'message' => 'Paiement effectué avec succès',
                'data' => new PaymentResource($payment)
            ], 201);
        } catch (\Exception $e) {
            Log::error('Erreur création paiement : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la création du paiement : ' . $e->getMessage()], 500);
        }
    }

    // Afficher un paiement spécifique
    public function show(string $id)
    {
        try {
            $payment = Payment::findOrFail($id);
            return response()->json(new PaymentResource($payment));
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Paiement non trouvé'], 404);
        } catch (\Exception $e) {
            Log::error('Erreur affichage paiement ID ' . $id . ' : ' . $e->getMessage());
            return response()->json(['message' => 'Paiement non trouvé'], 404);
        }
    }
    public function showUser(string $id)
    {
        try {
            $payment = Payment::whereHas('visaRequest', function ($query) use ($id) {
                $query->where('user_id', $id);
            })->get();
            return response()->json(['data' => PaymentResource::Collection($payment)]);
        } catch (\Exception $e) {
            Log::error('Erreur affichage paiement ID ' . $id . ' : ' . $e->getMessage());
        }
    }

    // Mettre à jour un paiement
    public function update(UpdatePaymentRequest $request, $id)
    {
        try {
            $payment = Payment::findOrFail($id);
            $payment->update($request->validated());

            return response()->json([
                'message' => 'Paiement mis à jour avec succès',
                'payment' => new PaymentResource($payment)
            ], 200);
        } catch (\Exception $e) {
            Log::error('Erreur mise à jour paiement ID ' . $id . ' : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la mise à jour du paiement : ' . $e->getMessage()], 500);
        }
    }

    // Supprimer un paiement
    public function destroy($id)
    {
        try {
            $payment = Payment::findOrFail($id);
            $payment->update(['status' => 'delete']);
            return response()->json(['message' => 'Paiement supprimé avec succès']);
        } catch (\Exception $e) {
            Log::error('Erreur suppression paiement ID ' . $id . ' : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la suppression du paiement : ' . $e->getMessage()], 500);
        }
    }

    // // Création d’un paiement
    // public function createPayment(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'amount' => 'required|numeric|min:100',
    //         'email' => 'required|email',
    //         'visa_request_id' => 'required|string',
    //         'payment_method' => 'required|string',
    //     ]);

    //     Log::info("Paiement request", $validatedData);

    //     try {
    //         $paymentData = [
    //             'amount' => intval($validatedData['amount']),
    //             'email' => $validatedData['email'],
    //             'currency' => 'XAF',
    //             'reference' => uniqid('ref_'),
    //             'callback' => env('APP_URL') . '/api/payement/callback',
    //             'channels' => $validatedData['payment_method'],
    //         ];

    //         $result = $this->notchPayService->initializePayment($paymentData);

    //         Log::info('Initialisation paiement NotchPay', ['result' => $result]);

    //         if (!empty($result['transaction']['reference'])) {
    //             $payment = Payment::create([
    //                 'visa_request_id' => $validatedData['visa_request_id'],
    //                 'amount' => $result['transaction']['amount'],
    //                 'currency' => 'XAF',
    //                 'status' => 'pending',
    //                 'transaction_id' => $result['transaction']['reference'],
    //                 'method' => $validatedData['payment_method'],
    //             ]);

    //             return response()->json([
    //                 'success' => true,
    //                 'authorization_url' => $result['authorization_url'],
    //                 'transaction_id' => $payment->transaction_id,
    //                 'customer' => $result['transaction']['customer'],
    //                 'reference' => $result['transaction']['reference'],
    //             ]);
    //         }

    //         return response()->json([
    //             'success' => false,
    //             'error' => 'Échec de l\'initialisation du paiement'
    //         ], 422);

    //     } catch (\Exception $e) {
    //         Log::error('Erreur paiement NotchPay : ' . $e->getMessage());
    //         return response()->json([
    //             'success' => false,
    //             'error' => 'Erreur serveur'
    //         ], 500);
    //     }
    // }

    // // Callback NotchPay (POST)
    // public function handleCallback(Request $request)
    // {
    //     $data = $request->all();
    //     Log::info('Callback paiement reçu', $data);

    //     if (isset($data['reference'])) {
    //         $payment = Payment::where('transaction_id', $data['reference'])->first();

    //         if ($payment) {
    //             $payment->update([
    //                 'status' => $data['status'] ?? 'failed',
    //                 'method' => $data['channel'] ?? $payment->method,
    //             ]);

    //             Log::info('Paiement mis à jour', [
    //                 'transaction_id' => $payment->transaction_id,
    //                 'status' => $payment->status
    //             ]);
    //         } else {
    //             Log::warning('Webhook reçu pour transaction inconnue', [
    //                 'reference' => $data['reference']
    //             ]);
    //         }
    //     }

    //     return response()->json(['success' => true]);
    // }
}
