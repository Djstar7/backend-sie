<?php

namespace App\Http\Controllers\Api;

use App\Models\Country;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\CountryStoreRequest;
use App\Http\Requests\CountryUpdateRequest;
use App\Http\Resources\CountryResource;
use Exception;

class CountryController extends Controller
{
    // Liste de tous les pays
    public function index()
    {
        try {
            $countries = Country::all();

            if ($countries->isEmpty()) {
                return response()->json(['message' => 'Aucun pays trouvé'], 404);
            }

            // Retour via Resource, pas de message de succès
            return CountryResource::collection(['data' => $countries]);
        } catch (Exception $e) {
            Log::error('Erreur lors de la récupération des pays : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la récupération des pays'], 500);
        }
    }

    // Création d’un pays
    public function store(CountryStoreRequest $request)
    {
        try {
            $country = Country::create($request->validated());
            return response()->json(['message' => 'Pays ajouté avec succès', 'data' => new CountryResource($country)], 201);
        } catch (Exception $e) {
            Log::error('Erreur lors de la création d’un pays : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la création du pays'], 500);
        }
    }

    // Afficher un pays
    public function show($id)
    {
        try {
            $country = Country::findOrFail($id);
            return response()->json(new CountryResource($country));
        } catch (Exception $e) {
            Log::error('Erreur lors de la récupération du pays : ' . $e->getMessage());
            return response()->json(['message' => 'Pays non trouvé'], 404);
        }
    }

    // Mise à jour d’un pays
    public function update(CountryUpdateRequest $request, $id)
    {
        try {
            $country = Country::findOrFail($id);
            $country->update($request->validated());
            return response()->json(['message' => 'Pays mis à jour avec succès']);
        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour du pays : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la mise à jour du pays'], 500);
        }
    }

    // Suppression d’un pays
    public function destroy($id)
    {
        try {
            $country = Country::findOrFail($id);
            $country->delete();
            return response()->json(['message' => 'Pays supprimé avec succès']);
        } catch (Exception $e) {
            Log::error('Erreur lors de la suppression du pays : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la suppression du pays'], 500);
        }
    }
}
