<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VisaRequestFormRequest;
use App\Http\Requests\VisaRequestStoreRequest;
use App\Http\Requests\VisaRequestUpdateRequest;
use App\Http\Resources\VisaRequestResource;
use App\Models\Country;
use App\Models\VisaRequest;
use App\Models\VisaType;
use Illuminate\Support\Facades\Log;

class VisaRequestController extends Controller
{
    // Liste toutes les demandes de visa
    public function index()
    {
        try {
            $visaRequests = VisaRequest::with(['user', 'originCountry', 'destinationCountry', 'visaType'])->get();
            return response()->json(
                ['data' => VisaRequestResource::collection($visaRequests)],
            );
        } catch (\Exception $e) {
            Log::error('Erreur index VisaRequest: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur lors de la récupération des demandes'], 500);
        }
    }

    // Créer une nouvelle demande de visa
    public function store(VisaRequestStoreRequest $visaRequestStoreRequest)
    {
        try {
            $request = $visaRequestStoreRequest->validated();
            $visaType = VisaType::where('name', $request['visa_type_name'])->firstOrFail();
            $nationality = Country::where('name', $request['nationality'])->firstOrFail();
            $countryDest = Country::where('name', $request['country_dest_name'])->firstOrFail();

            $visaRequest = VisaRequest::create([
                'user_id' => $request['user_id'],
                'visa_type_id' => $visaType->id,
                'origin_country_id' => $nationality->id,
                'destination_country_id' => $countryDest->id,
            ]);

            return response()->json([
                'data' => new VisaRequestResource($visaRequest),
                'message' => 'Visa request créée avec succès'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Erreur store VisaRequest: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur lors de la création de la demande'], 500);
        }
    }

    // Afficher une demande de visa spécifique
    public function show($id)
    {
        try {
            $visaRequest = VisaRequest::with(['user', 'originCountry', 'destinationCountry', 'visaType'])->findOrFail($id);
            return response()->json([
                'data' => new VisaRequestResource($visaRequest),
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur show VisaRequest: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur lors de la récupération de la demande'], 500);
        }
    }

    // Afficher les demandes d'un utilisateur
    public function showByUser($userId)
    {
        try {
            $visaRequests = VisaRequest::with(['user', 'originCountry', 'destinationCountry', 'visaType'])
                ->where('user_id', $userId)
                ->orderByDesc('updated_at')
                ->get();
            return response()->json([
                'data' => VisaRequestResource::collection($visaRequests),
                'message' => 'Visa requests de l\'utilisateur récupérées avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur showByUser VisaRequest: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur lors de la récupération des demandes'], 500);
        }
    }

    // Mettre à jour une demande de visa
    public function update(VisaRequestUpdateRequest $visaRequestUpdateRequest, $id)
    {
        try {
            $request = $visaRequestUpdateRequest->validated();
            Log::info('Request VisaRequest Update', $request);
            $visaRequest = VisaRequest::findOrFail($id);
            $visaRequest->update(['status' => $request['status'] ?? $request->status]);

            return response()->json([
                'data' => new VisaRequestResource($visaRequest),
                'message' => 'Statut de la demande mis à jour avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur update VisaRequest: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur lors de la mise à jour de la demande'], 500);
        }
    }

    // Supprimer une demande de visa
    public function destroy($id)
    {
        try {
            $visaRequest = VisaRequest::findOrFail($id);
            $visaRequest->delete();

            return response()->json(['message' => 'Visa request supprimée avec succès']);
        } catch (\Exception $e) {
            Log::error('Erreur destroy VisaRequest: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur lors de la suppression de la demande'], 500);
        }
    }
}
