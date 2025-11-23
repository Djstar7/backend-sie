<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppoitmentStoreAndUpdateRequest;
use App\Http\Requests\AppoitmentUpdateByUserRequest;
use App\Http\Resources\AppoitmentResource;
use App\Models\Appoitment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Exception;

class AppoitmentController extends Controller
{
    /**
     * Liste de tous les rendez-vous
     */
    public function index(): JsonResponse
    {
        try {
            $appointments = Appoitment::with('visaRequest')->orderByDesc('updated_at')->get();

            if ($appointments->isEmpty()) {
                return response()->json(['message' => 'Aucun rendez-vous trouvé.'], 404);
            }

            // Données seules, pas de message de succès
            return response()->json(['data' => AppoitmentResource::collection($appointments)]);
        } catch (Exception $e) {
            Log::error('Erreur lors de la récupération des rendez-vous : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la récupération des rendez-vous'], 500);
        }
    }

    /**
     * Enregistrer un nouveau rendez-vous
     */
    public function store(AppoitmentStoreAndUpdateRequest $request): JsonResponse
    {
        try {
            $appoitment = Appoitment::create($request->validated());
            return response()->json(['message' => 'Rendez-vous créé avec succès', 'data' => $appoitment], 201);
        } catch (Exception $e) {
            Log::error('Erreur lors de la création de rendez-vous : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la création du rendez-vous'], 500);
        }
    }

    /**
     * Afficher un rendez-vous précis
     */
    public function show(string $id): JsonResponse
    {
        try {
            $appointment = Appoitment::with('visaRequest')->find($id);
            if (!$appointment) {
                return response()->json(['message' => 'Rendez-vous non trouvé'], 404);
            }

            return response()->json(new AppoitmentResource($appointment));
        } catch (Exception $e) {
            Log::error('Erreur lors de la récupération du rendez-vous : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la récupération du rendez-vous'], 500);
        }
    }

    /**
     * Afficher les rendez-vous liés à une demande de visa
     */
    public function showByVisaRequest(string $id): JsonResponse
    {
        try {
            $appoitments = Appoitment::where('visa_request_id', $id)
                ->with('visaRequest')
                ->get();
            return response()->json(['data' => AppoitmentResource::collection($appoitments)]);
        } catch (Exception $e) {
            Log::error('Erreur lors de la récupération des rendez-vous associés : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la récupération des rendez-vous associés'], 500);
        }
    }
    public function showByUser(string $id): JsonResponse
    {
        try {
            $appoitments = Appoitment::whereHas('visaRequest', function ($query) use ($id) {
                $query->where('user_id',  $id);
            })->get();
            return response()->json(['data' => AppoitmentResource::collection($appoitments)]);
        } catch (Exception $e) {
            Log::error('Erreur lors de la récupération des rendez-vous associés : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la récupération des rendez-vous associés'], 500);
        }
    }

    /**
     * Mettre à jour un rendez-vous
     */
    public function update(AppoitmentStoreAndUpdateRequest $request, string $id): JsonResponse
    {
        try {
            $appointment = Appoitment::find($id);

            if (!$appointment) {
                return response()->json(['message' => 'Rendez-vous non trouvé'], 404);
            }

            $appointment->update($request->validated());

            return response()->json(['message' => 'Rendez-vous mis à jour avec succès']);
        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour de rendez-vous : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la mise à jour du rendez-vous'], 500);
        }
    }

    /**
     * Mise à jour par l'utilisateur (champ limité)
     */
    public function updateByUser(AppoitmentUpdateByUserRequest $request, string $id): JsonResponse
    {
        try {
            $appointment = Appoitment::find($id);

            if (!$appointment) {
                return response()->json(['message' => 'Rendez-vous non trouvé'], 404);
            }

            $appointment->update($request->validated());

            return response()->json(['message' => 'Rendez-vous mis à jour avec succès']);
        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour du rendez-vous par utilisateur : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la mise à jour du rendez-vous'], 500);
        }
    }

    /**
     * Supprimer un rendez-vous
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $appointment = Appoitment::find($id);

            if (!$appointment) {
                return response()->json(['message' => 'Rendez-vous non trouvé'], 404);
            }

            $appointment->delete();

            return response()->json(['message' => 'Rendez-vous supprimé avec succès']);
        } catch (Exception $e) {
            Log::error('Erreur lors de la suppression de rendez-vous : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la suppression du rendez-vous'], 500);
        }
    }
}
