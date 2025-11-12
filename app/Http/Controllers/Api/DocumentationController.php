<?php

namespace App\Http\Controllers\Api;

use App\Models\Documentation;
use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentationRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\DocumentationResource;
use Exception;

class DocumentationController extends Controller
{
    // Liste toutes les documentations
    public function index()
    {
        try {
            $docs = Documentation::all();

            if ($docs->isEmpty()) {
                return response()->json(['message' => 'Aucune documentation trouvée'], 404);
            }

            // Données seules, via resource
            return DocumentationResource::collection($docs);
        } catch (Exception $e) {
            Log::error('Erreur lors de la récupération des documentations : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la récupération des documentations'], 500);
        }
    }

    // Créer une documentation
    public function store(DocumentationRequest $request)
    {
        try {
            $documentation = Documentation::create($request->validated());
            return response()->json(['message' => 'Documentation créée avec succès', 'data' => new Documentation($documentation)], 201);
        } catch (Exception $e) {
            Log::error('Erreur lors de la création de la documentation : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la création de la documentation'], 500);
        }
    }

    // Afficher une documentation
    public function show($id)
    {
        try {
            $doc = Documentation::findOrFail($id);
            return response()->json(new DocumentationResource($doc));
        } catch (Exception $e) {
            Log::error('Erreur lors de la récupération de la documentation : ' . $e->getMessage());
            return response()->json(['message' => 'Documentation non trouvée'], 404);
        }
    }

    // Mettre à jour une documentation
    public function update(DocumentationRequest $request, $id)
    {
        try {
            $doc = Documentation::findOrFail($id);
            $doc->update($request->validated());
            return response()->json(['message' => 'Documentation mise à jour avec succès']);
        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour de la documentation : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la mise à jour de la documentation'], 500);
        }
    }

    // Supprimer une documentation
    public function destroy($id)
    {
        try {
            $doc = Documentation::findOrFail($id);
            $doc->delete();
            return response()->json(['message' => 'Documentation supprimée avec succès']);
        } catch (Exception $e) {
            Log::error('Erreur lors de la suppression de la documentation : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la suppression de la documentation'], 500);
        }
    }
}
