<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VisaStoreRequest;
use App\Http\Requests\VisaStorestoreRequest;
use App\Http\Requests\VisaUpdateRequest;
use App\Http\Resources\VisaResource;
use App\Models\Country;
use App\Models\CountryVisaType;
use App\Models\User;
use App\Models\VisaType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VisaController extends Controller
{
    public function index()
    {
        try {
            $countryVisaTypes = CountryVisaType::with(['country', 'visaType'])->paginate(15);

            return VisaResource::collection($countryVisaTypes);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération de la liste des visas: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur serveur lors de la récupération de la liste des visas',
            ], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $eligibility = CountryVisaType::with(['country', 'visaType', 'requiredDocuments'])->findOrFail($id);

            return response()->json([
                'data' => [
                    'id' => $eligibility->id,
                    'country' => $eligibility->country?->name,
                    'visa_type' => $eligibility->visaType?->name,
                    'price_base' => $eligibility->price_base,
                    'price_per_child' => $eligibility->price_per_child,
                    'processing_duration_min' => $eligibility->processing_duration_min,
                    'processing_duration_max' => $eligibility->processing_duration_max,
                    'status_mat' => $eligibility->status_mat,
                    'min_age' => $eligibility->min_age,
                    'max_age' => $eligibility->max_age,
                    'documents' => $eligibility->requiredDocuments->pluck('name')->toArray(),
                ],
                'message' => 'Details de l\'eligibilite recuperes avec succes',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Eligibilite non trouvee.'], 404);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la recuperation de l\'eligibilite: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur serveur lors de la recuperation de l\'eligibilite',
            ], 500);
        }
    }

    public function store(VisaStoreRequest $request)
    {
        try {
            $data = $request->validated();

            DB::transaction(function () use ($data) {
                CountryVisaType::createEligibilities($data);
            });

            return response()->json(['message' => 'Eligibilite(s) creee(s) avec succes'], 201);
        } catch (\Exception $e) {
            Log::error("Erreur lors de la creation de l'eligibilite: " . $e->getMessage());
            return response()->json([
                'message' => "Erreur serveur lors de la creation de l'eligibilite"
            ], 500);
        }
    }

    public function storestore(VisaStorestoreRequest $request)
    {
        try {
            $data = $request->validated();

            $user = User::with('profil.country')->findOrFail($data['user_id']);
            $profil = $user->profil;
            $age = Carbon::parse($profil->date_of_birth)->age;

            $country = Country::where('name', $data['country_dest_name'])->firstOrFail();
            $visaType = VisaType::where('name', $data['visa_type_name'])->firstOrFail();

            // Trouver l'eligibilite correspondant au profil de l'utilisateur
            $eligibility = CountryVisaType::with('requiredDocuments')
                ->where('country_id', $country->id)
                ->where('visa_type_id', $visaType->id)
                ->where('status_mat', $profil->status_mat)
                ->where('min_age', '<=', $age)
                ->where('max_age', '>=', $age)
                ->first();

            if (!$eligibility) {
                // Fallback: chercher sans criteres stricts
                $eligibility = CountryVisaType::with('requiredDocuments')
                    ->where('country_id', $country->id)
                    ->where('visa_type_id', $visaType->id)
                    ->first();
            }

            if (!$eligibility) {
                return response()->json([
                    'message' => 'Aucune configuration de visa trouvee pour cette destination et ce type.'
                ], 404);
            }

            return response()->json([
                'data' => new VisaResource($eligibility),
                'orthers' => [
                    'status_mat' => $profil->status_mat,
                    'user_id' => $user->id,
                    'nationality' => $profil->country?->name,
                ],
                'message' => 'Documents requis recuperes avec succes',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Ressource introuvable (pays ou type de visa).'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Erreur recuperation documents requis', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Recuperation des documents requis impossible en raison d\'une erreur interne.'
            ], 500);
        }
    }

    public function update(string $id, VisaUpdateRequest $request)
    {
        try {
            $data = $request->validated();

            DB::transaction(function () use ($id, $data) {
                $eligibility = CountryVisaType::findOrFail($id);
                $eligibility->updateEligibility($data);
            });

            return response()->json(['message' => 'Eligibilite mise a jour avec succes'], 200);
        } catch (\Exception $e) {
            Log::error('Erreur mise a jour eligibilite: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur serveur lors de la mise a jour de l\'eligibilite',
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $eligibility = CountryVisaType::findOrFail($id);
            $eligibility->requiredDocuments()->detach();
            $eligibility->delete();

            return response()->json(['message' => 'Eligibilite supprimee avec succes'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Eligibilite non trouvee.'], 404);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de l\'eligibilite: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur serveur lors de la suppression de l\'eligibilite',
            ], 500);
        }
    }
}
