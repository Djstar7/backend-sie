<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VisaTypeStoreRequest;
use App\Http\Requests\VisaTypeUpdateRequest;
use App\Http\Resources\VisaTypeResource;
use App\Models\VisaType;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class VisaTypeController extends Controller
{
    /**
     * Afficher la liste des types de visa.
     */
    public function index(): JsonResponse
    {
        try {
            $visaTypes = VisaType::all();

            if ($visaTypes->isEmpty()) {
                return response()->json(['message' => 'Aucun type de visa trouvé'], 404);
            }

            return response()->json([
                'data' => VisaTypeResource::collection($visaTypes),
            ], 200);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des types de visa : ' . $e->getMessage());

            return response()->json(['message' => 'Erreur lors de la recuperation'], 500);
        }
    }

    /**
     * Enregistrer un nouveau type de visa.
     */
    public function store(VisaTypeStoreRequest $request): JsonResponse
    {
        try {
            $visaType = VisaType::create($request->validated());

            return response()->json([
                'message' => 'Type de visa ajouté avec succès',
                'data' => new VisaTypeResource($visaType),
            ], 201);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création d’un type de visa : ' . $e->getMessage());

            return response()->json(['message' => 'Erreur lors de la création du type de visa'], 500);
        }
    }

    /**
     * Afficher un type de visa spécifique.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $visaType = VisaType::findOrFail($id);
            return response()->json([
                'data' => new VisaTypeResource($visaType),
            ], 200);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l’affichage d’un type de visa : ' . $e->getMessage());

            return response()->json(['message' => 'Erreur lors de la récupération du type de visa'], 500);
        }
    }

    /**
     * Mettre à jour un type de visa.
     */
    public function update(VisaTypeUpdateRequest $request, string $id): JsonResponse
    {
        try {
            $visaType = VisaType::findOrFail($id);
            $visaType->update($request->validated());

            return response()->json([
                'message' => 'Type de visa mis à jour avec succès',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du type de visa : ' . $e->getMessage());

            return response()->json(['message' => 'Erreur lors de la mise à jour du type de visa'], 500);
        }
    }

    /**
     * Supprimer un type de visa.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $visaType = VisaType::findOrFail($id);
            $visaType->delete();

            return response()->json(['message' => 'Type de visa supprimé avec succès'], 200);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du type de visa : ' . $e->getMessage());

            return response()->json(['message' => 'Erreur lors de la suppression du type de visa'], 500);
        }
    }
}
