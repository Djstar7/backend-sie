<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VisaStoreRequest;
use App\Http\Requests\VisaStorestoreRequest;
use App\Http\Requests\VisaUpdateRequest;
use App\Http\Resources\RequiredDocumentResource;
use App\Http\Resources\VisaResource;
use App\Models\Country;
use App\Models\CountryVisaType;
use App\Models\RequiredDocument;
use App\Models\User;
use App\Models\VisaType;
use DB;
use Illuminate\Support\Carbon;
use Log;

class VisaController extends Controller
{
    public function store(VisaStoreRequest $visaStoreRequest)
    {
        try {
            $request = $visaStoreRequest->validated();
            DB::transaction(function () use ($request) {
                $country = Country::where('name', $request->country_name)->firstOrFail();
                $visaType = VisaType::where('name', $request->visa_type_name)->firstOrFail();

                $countryVisaType = CountryVisaType::updateOrCreate(
                    ['country_id' => $country->id, 'visa_type_id' => $visaType->id],
                    [
                        'price_base' => $request->price_base,
                        'price_per_child' => $request->price_per_child ?? 0,
                        'processing_duration_min' => $request->processing_duration_min,
                        'processing_duration_max' => $request->processing_duration_max,
                    ]
                );

                $documentIds = [];
                foreach ($request->documents as $docName) {
                    $document = RequiredDocument::firstOrCreate([
                        'name' => $docName,
                        'status_mat' => $request->status_mat,
                        'age' => $request->age
                    ]);
                    $documentIds[] = $document->id;
                }

                $countryVisaType->requiredDocuments()->syncWithoutDetaching($documentIds);
            });

            return response()->json(['message' => 'Visa et documents enregistrés avec succès'], 201);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'enregistrement du visa: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur lors de l\'enregistrement du visa'], 500);
        }
    }

    public function storestore(VisaStorestoreRequest $visaStoreRequest)
    {
        try {
            $request = $visaStoreRequest->validated();
            $user = User::findOrFail($request->user_id);
            $age = Carbon::parse($user->date_of_birth)->age;

            $country = Country::where('name', $request->country_dest_name)->firstOrFail();
            $visaType = VisaType::where('name', $request->visa_type_name)->firstOrFail();

            $countryVisaType = CountryVisaType::where('country_id', $country->id)
                ->where('visa_type_id', $visaType->id)
                ->where('age_max', '>=', $age)
                ->where('age_min', '<=', $age)
                ->firstOrFail();

            $documents = $countryVisaType->requiredDocuments()
                ->where(function ($q) use ($user) {
                    $q->whereNull('status_mat')->orWhere('status_mat', $user->status_mat);
                })
                ->get();

            return response()->json([
                'documents' => RequiredDocumentResource::collection($documents),
                'data' => new VisaResource($countryVisaType),
                'message' => 'Documents requis récupérés avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur récupération documents requis: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur: ' . $e->getMessage()], 500);
        }
    }

    public function update(VisaUpdateRequest $request, $countryVisaTypeId)
    {
        try {
            $validated = $request->validated();
            $countryVisaType = CountryVisaType::findOrFail($countryVisaTypeId);
            $countryVisaType->update($validated);

            return response()->json([
                'data' => new VisaResource($countryVisaType),
                'message' => 'Visa mis à jour avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur mise à jour visa: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur lors de la mise à jour du visa'], 500);
        }
    }
}
