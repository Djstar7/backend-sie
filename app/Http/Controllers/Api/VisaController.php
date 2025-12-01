<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VisaStoreRequest;
use App\Http\Requests\VisaStorestoreRequest;
use App\Http\Requests\VisaUpdateRequest;
use App\Http\Resources\VisaResource;
use App\Models\Country;
use App\Models\CountryVisaType;
use App\Models\Profil;
use App\Models\RequiredDocument;
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

    public function show(string $countryVisaTypeId)
    {
        try {
            $countryVisaType = CountryVisaType::with([
                'country',
                'visaType',
                'requiredDocuments'
            ])->findOrFail($countryVisaTypeId);

            return response()->json([
                'data' => new VisaResource($countryVisaType),
                'message' => 'Détails du visa récupérés avec succès',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Visa non trouvé.'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération du détail du visa: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur serveur lors de la récupération du détail du visa',
            ], 500);
        }
    }

    public function store(VisaStoreRequest $visaStoreRequest)
    {
        try {
            $data = $visaStoreRequest->validated();

            DB::transaction(function () use ($data) {
                $country = Country::where('name', $data['country_name'])->firstOrFail();
                $visaType = VisaType::where('name', $data['visa_type_name'])->firstOrFail();

                $countryVisaType = CountryVisaType::updateOrCreate(
                    ['country_id' => $country->id, 'visa_type_id' => $visaType->id],
                    [
                        'price_base' => $data['price_base'],
                        'price_per_child' => $data['price_per_child'] ?? 0,
                        'processing_duration_min' => $data['processing_duration_min'],
                        'processing_duration_max' => $data['processing_duration_max'],
                    ]
                );

                $documentIds = [];
                foreach ($data['documents'] as $docName) {
                    $document = RequiredDocument::firstOrCreate(
                        [
                            'name' => $docName,
                            'status_mat' => $data['status_mat'],
                            'min_age' => $data['min_age'],
                            'max_age' => $data['max_age'],
                        ],
                        [
                            'name' => $docName,
                            'status_mat' => $data['status_mat'],
                            'min_age' => $data['min_age'],
                            'max_age' => $data['max_age'],
                        ]
                    );

                    $documentIds[] = $document->id;
                }

                $countryVisaType->requiredDocuments()->syncWithoutDetaching($documentIds);
            });

            return response()->json(['message' => 'Visa et documents enregistrés avec succès'], 201);
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'enregistrement du visa: " . $e->getMessage());
            return response()->json([
                'message' => "Erreur serveur lors de l'enregistrement du visa"
            ], 500);
        }
    }


    public function storestore(VisaStorestoreRequest $visaStoreRequest)
    {
        try {
            $data = $visaStoreRequest->validated();

            $user = User::with('profil')->findOrFail($data['user_id']);
            $profil =  $user->profil;
            $age = Carbon::parse($profil->date_of_birth)->age;

            $country = Country::where('name', $data['country_dest_name'])->firstOrFail();
            $visaType = VisaType::where('name', $data['visa_type_name'])->firstOrFail();

            $countryVisaType = CountryVisaType::where('country_id', $country->id)
                ->where('visa_type_id', $visaType->id)
                ->firstOrFail();

            $countryVisaType->load(['requiredDocuments' => function ($q) use ($profil, $age) {
                $q->where(function ($query) use ($profil) {
                    $query->whereNull('status_mat')
                        ->orWhere('status_mat', $profil->status_mat);
                })
                    ->where('max_age', '>=', $age)
                    ->where('min_age', '<=', $age);
            }]);



            return response()->json([
                'data' => new VisaResource($countryVisaType),
                'orthers' => ['status_mat' => $profil->status_mat,  'user_id' => $user->id, 'nationality' => $profil->country->name],
                'message' => 'Documents requis récupérés avec succès',
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur récupération documents requis: ' . $e->getMessage());
            return response()->json([
                'message' => 'récupération documents requis'
            ], 500);
        }
    }

    public function update(string $countryVisaTypeId, VisaUpdateRequest $request)
    {
        try {
            $validated = $request->validated();

            $countryVisaType = CountryVisaType::findOrFail($countryVisaTypeId);
            $countryVisaType->update($validated);

            return response()->json([
                'data' => new VisaResource($countryVisaType),
                'message' => 'Visa mis à jour avec succès',
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur mise à jour visa: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur serveur lors de la mise à jour du visa',
            ], 500);
        }
    }

    public function destroy(string $countryVisaTypeId)
    {
        try {
            $countryVisaType = CountryVisaType::findOrFail($countryVisaTypeId);

            $countryVisaType->requiredDocuments()->detach();

            $countryVisaType->delete();

            return response()->json([
                'message' => 'Visa supprimé avec succès',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Visa non trouvé.'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du visa: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur serveur lors de la suppression du visa',
            ], 500);
        }
    }
}
