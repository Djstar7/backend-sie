<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentStoreRequest;
use App\Http\Requests\DocumentUpdateRequest;
use App\Http\Requests\DocumentUpdateStatusRequest;
use App\Http\Resources\DocumentResource;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Exception;

class DocumentController extends Controller
{
    // Liste tous les documents
    public function index()
    {
        try {
            $documents = Document::all();

            if ($documents->isEmpty()) {
                return response()->json(['message' => 'Aucun document trouvé'], 404);
            }

            return DocumentResource::collection($documents);
        } catch (Exception $e) {
            Log::error('Erreur lors de la récupération des documents : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la récupération des documents'], 500);
        }
    }

    // Ajouter un document
    public function store(DocumentStoreRequest $documentStoreRequest)
    {
        try {
            $request = $documentStoreRequest->validated();
            $file = $request->file('document_file');
            $filename = Str::slug($request->name) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('visa_documents', $filename, 'public');

            $document = Document::create([
                'visa_request_id' => $request->visa_request_id,
                'name' => $request->name,
                'file_path' => $filePath,
                'is_validated' => $request->is_validated ?? false,
            ]);

            return response()->json(['message' => 'Document créé avec succès', 'data' => new DocumentResource($document)], 201);
        } catch (Exception $e) {
            Log::error('Erreur lors de la création du document : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la création du document'], 500);
        }
    }

    // Afficher un document
    public function show($id)
    {
        try {
            $document = Document::with('visaRequest')->findOrFail($id);
            return response()->json(new DocumentResource($document));
        } catch (Exception $e) {
            Log::error('Document non trouvé : ' . $e->getMessage());
            return response()->json(['message' => 'Document non trouvé'], 404);
        }
    }

    // Mettre à jour un document
    public function update(DocumentUpdateRequest $documentUpdate, $id)
    {
        try {
            $document = Document::findOrFail($id);
            $request = $documentUpdate->validated();

            if ($request->hasFile('document_file')) {
                if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                    Storage::disk('public')->delete($document->file_path);
                }
                $file = $request->file('document_file');
                $filename = Str::slug($request->name) . '_' . time() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('visa_documents', $filename, 'public');
            } else {
                $filePath = $document->file_path;
            }

            $document->update([
                'visa_request_id' => $request->visa_request_id,
                'name' => $request->name,
                'file_path' => $filePath,
                'is_validated' => $request->is_validated ?? $document->is_validated,
            ]);

            return new DocumentResource($document);
        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour du document : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la mise à jour du document'], 500);
        }
    }

    // Supprimer un document
    public function destroy($id)
    {
        try {
            $document = Document::findOrFail($id);

            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            $document->delete();

            return response()->json(['message' => 'Document supprimé avec succès 🗑️'], 200);
        } catch (Exception $e) {
            Log::error('Erreur lors de la suppression du document : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la suppression du document'], 500);
        }
    }

    // Documents par utilisateur
    public function getByUser($userId)
    {
        try {
            $documents = Document::with('visaRequest')->where('user_id', $userId)->get();
            if ($documents->isEmpty()) {
                return response()->json(['message' => 'Aucun document trouvé pour cet utilisateur'], 404);
            }
            return DocumentResource::collection($documents);
        } catch (Exception $e) {
            Log::error('Erreur lors de la récupération des documents par utilisateur : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la récupération des documents'], 500);
        }
    }

    // Documents par demande de visa
    public function getByVisaRequest($visaRequestId)
    {
        try {
            $documents = Document::where('visa_request_id', $visaRequestId)->get();
            if ($documents->isEmpty()) {
                return response()->json(['message' => 'Aucun document trouvé pour cette demande'], 404);
            }
            return DocumentResource::collection($documents);
        } catch (Exception $e) {
            Log::error('Erreur lors de la récupération des documents par demande : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la récupération des documents'], 500);
        }
    }

    // Mise à jour du statut uniquement
    public function updateStatus(DocumentUpdateStatusRequest $request, $id)
    {
        try {
            $document = Document::findOrFail($id);
            $validated = $request->validated();

            $document->update($validated);
            return new DocumentResource($document);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => $e->validator->errors()->all()], 422);
        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour du statut : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la mise à jour du document'], 500);
        }
    }
}
