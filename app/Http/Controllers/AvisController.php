<?php

namespace App\Http\Controllers;

use App\Models\Avis;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\AvisResource;
use App\Http\Requests\StoreAvisRequest;
use App\Http\Requests\UpdateAvisRequest;

class AvisController extends Controller
{
    /**
     * Liste de toutes les aviss
     */
    public function index()
    {
        try {
            $aviss = Avis::latest()->get();
            return AvisResource::collection($aviss);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'affichage des aviss : ' . $e->getMessage());

            return response()->json([
                'message' => 'Impossible de récupérer la liste.'
            ], 500);
        }
    }

    /**
     * Création d'une avis
     */
    public function store(StoreAvisRequest $request)
    {
        try {
            $avis = Avis::create($request->validated());

            return new AvisResource($avis);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de avis : ' . $e->getMessage());

            return response()->json([
                'message' => 'Impossible de créer cette avis.'
            ], 500);
        }
    }

    /**
     * Détails d'une avis
     */
    public function show($id)
    {
        try {
            $avis = Avis::findOrFail($id);

            return new AvisResource($avis);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'affichage d\'une avis : ' . $e->getMessage());

            return response()->json([
                'message' => 'avis introuvable.'
            ], 404);
        }
    }

    /**
     * Mise à jour d'une avis
     */
    public function update(UpdateAvisRequest $request, $id)
    {
        try {
            $avis = Avis::findOrFail($id);
            $avis->update($request->validated());

            return new AvisResource($avis);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de avis : ' . $e->getMessage());

            return response()->json([
                'message' => 'Impossible de mettre à jour cette avis.'
            ], 500);
        }
    }

    /**
     * Suppression d'une avis
     */
    public function destroy($id)
    {
        try {
            $avis = Avis::findOrFail($id);
            $avis->delete();

            return response()->json([
                'message' => 'avis supprimée avec succès.'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de avis : ' . $e->getMessage());

            return response()->json([
                'message' => 'Impossible de supprimer cette avis.'
            ], 500);
        }
    }
}
