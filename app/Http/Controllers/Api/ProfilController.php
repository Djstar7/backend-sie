<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfilRequest;
use App\Http\Resources\ProfilResource;
use App\Models\Profil;
use App\Models\Country;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ProfilController extends Controller
{
    /**
     * Liste tous les profils
     */
    public function index()
    {
        try {
            $profils = Profil::with('country', 'user')->get();
            return ProfilResource::collection($profils);
        } catch (\Exception $e) {
            Log::error('Erreur index ProfilController: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur lors de la récupération des profils'], 500);
        }
    }

    /**
     * Crée un nouveau profil
     */
    public function store(ProfilRequest $request)
    {
        try {
            $data = $request->validated();

            $country = Country::where('name', $data['nationality'])->first();
            $data['country_id'] = $country?->id;
            unset($data['nationality']);

            $profil = Profil::create($data);

            return response()->json(['message' => 'Profil créé avec succès', 'data' => new ProfilResource($profil)], 201);
        } catch (\Exception $e) {
            Log::error('Erreur store ProfilController: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur lors de la création du profil'], 500);
        }
    }

    /**
     * Affiche un profil spécifique
     */
    public function show(string $id)
    {
        try {
            $profil = Profil::with('country', 'user')->findOrFail($id);
            return response()->json(new ProfilResource($profil));
        } catch (\Exception $e) {
            Log::error('Erreur show ProfilController: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur lors de la récupération du profil'], 500);
        }
    }
    /**
     * Affiche un profil spécifique
     */
    public function showUser(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $profil = $user->profil;
            return response()->json(new ProfilResource($profil));
        } catch (\Exception $e) {
            Log::error('Erreur showUserController: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur lors de la récupération de user du profil'], 500);
        }
    }

    /**
     * Met à jour un profil
     */
    public function update(ProfilRequest $request, string $id)
    {
        try {
            $data = $request->validated();
            $profil = Profil::findOrFail($id);

            if (isset($data['nationality'])) {
                $country = Country::where('name', $data['nationality'])->first();
                $data['country_id'] = $country?->id;
                unset($data['nationality']);
            }

            $profil->update($data);

            return new ProfilResource($profil);
        } catch (\Exception $e) {
            Log::error('Erreur update ProfilController: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur lors de la mise à jour du profil'], 500);
        }
    }

    /**
     * Supprime un profil
     */
    public function destroy(Profil $profil)
    {
        try {
            $profil->tokens()->delete(); // Supprime les tokens si API Sanctum
            $profil->delete();

            return response()->json(['message' => 'Profil supprimé avec succès']);
        } catch (\Exception $e) {
            Log::error('Erreur destroy ProfilController: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur lors de la suppression du profil'], 500);
        }
    }
}
