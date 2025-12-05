<?php

namespace App\Http\Controllers\Api;

use App\Events\UserActionEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\AppoitmentStoreAndUpdateRequest;
use App\Http\Resources\AppoitmentResource;
use App\Models\Appoitment;
use App\Models\User;
use App\Models\VisaRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Auth;

class AppoitmentController extends Controller
{
    /**
     * Liste de tous les rendez-vous
     */
    public function index(): JsonResponse
    {
        try {
            $appoitments = Appoitment::with('visaRequest')->orderByDesc('updated_at')->get();

            if ($appoitments->isEmpty()) {
                return response()->json(['message' => 'Aucun rendez-vous trouvé.'], 404);
            }

            // Données seules, pas de message de succès
            return response()->json(['data' => AppoitmentResource::collection($appoitments)]);
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
            $visaRequest = VisaRequest::find($appoitment->visa_request_id);
            UserActionEvent::dispatch(User::find($visaRequest->user_id), [
                "type" => "Appoitment",
                "message" => "Veillez choisir la date qui vous convient pour venir avec vos document en physique pour terminer la procedure",
                "link" => "/custom/visarequest/show/$appoitment->visa_request_id"
            ]);
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
            $appoitment = Appoitment::with('visaRequest')->find($id);
            if (!$appoitment) {
                return response()->json(['message' => 'Rendez-vous non trouvé'], 404);
            }

            return response()->json(new AppoitmentResource($appoitment));
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
     * Mise à jour par l'utilisateur (champ limité)
     */
    public function updateByUser(AppoitmentStoreAndUpdateRequest $request, string $id): JsonResponse
    {
        try {
            $appoitment = Appoitment::find($id);

            if (!$appoitment) {
                return response()->json(['message' => 'Rendez-vous non trouvé'], 404);
            }
            $appoitments = Appoitment::where('status', 'rescheduled')->get();

            foreach ($appoitments as $appoit) {
                $appoit->update(['status' => 'pending']);
            }

            $appoitment->update($request->validated());

            UserActionEvent::dispatch(Auth::user(), [
                "type" => "Appoitment",
                "message" => "Vous avez approvez avec success la date du $appoitment->scheduled_at pour vous rendre au services agant avec les document approuves par note agents",
                "link" => "/custom/visarequest/show/$appoitment->visa_request_id"
            ]);
            $agents = User::whereHas('roles', function ($q) {
                $q->where('name', 'agent');
            });
            foreach ($agents as $ag) {
                UserActionEvent::dispatch($ag, [
                    "type" => "Appoitment",
                    "author" => User::find($appoitment->user_id)->name,
                    "message" => "Le client  a approuver de ce rendre a votre service le $appoitment->scheduled_at pour la finalisation du traitement de sa demande d'identifiant",
                    "link" => "/agent/users/$appoitment->user_id/visarequest/$appoitment->visa_request_id"
                ]);
            }


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
            $appoitment = Appoitment::find($id);

            if (!$appoitment) {
                return response()->json(['message' => 'Rendez-vous non trouvé'], 404);
            }

            $appoitment->delete();

            return response()->json(['message' => 'Rendez-vous supprimé avec succès']);
        } catch (Exception $e) {
            Log::error('Erreur lors de la suppression de rendez-vous : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la suppression du rendez-vous'], 500);
        }
    }
}
