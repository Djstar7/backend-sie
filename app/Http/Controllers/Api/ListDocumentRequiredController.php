<?php

namespace App\Http\Controllers\Api;

use App\Models\ListDocumentRequired;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class ListDocumentRequiredController extends Controller
{
    /**
     * Liste de tous les documents requis
     */
    public function index(Request $request)
    {
        try {
            $query = ListDocumentRequired::query();

            // Filtrer par statut actif si demande
            if ($request->has('active_only') && $request->active_only) {
                $query->active();
            }

            // Filtrer par categorie si demande
            if ($request->has('category') && $request->category) {
                $query->byCategory($request->category);
            }

            $documents = $query->orderBy('category')->orderBy('name')->get();

            return response()->json(['data' => $documents]);
        } catch (Exception $e) {
            Log::error('Erreur lors de la recuperation des documents requis : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la recuperation des documents'], 500);
        }
    }

    /**
     * Liste groupee par categorie (pour le frontend)
     */
    public function indexGrouped(Request $request)
    {
        try {
            $query = ListDocumentRequired::query();

            if ($request->has('active_only') && $request->active_only) {
                $query->active();
            }

            $documents = $query->orderBy('name')->get();

            // Grouper par categorie
            $grouped = $documents->groupBy('category')->map(function ($items) {
                return $items->values();
            });

            return response()->json(['data' => $grouped]);
        } catch (Exception $e) {
            Log::error('Erreur lors de la recuperation groupee des documents : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la recuperation des documents'], 500);
        }
    }

    /**
     * Liste des categories disponibles
     */
    public function categories()
    {
        try {
            $categories = ListDocumentRequired::getCategories();
            return response()->json(['data' => $categories]);
        } catch (Exception $e) {
            Log::error('Erreur lors de la recuperation des categories : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la recuperation des categories'], 500);
        }
    }

    /**
     * Creation d'un document requis
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'guide' => 'nullable|string',
                'category' => 'required|string|in:' . implode(',', ListDocumentRequired::getCategories()),
                'is_required' => 'boolean',
                'file_types' => 'required|array|min:1',
                'file_types.*' => 'string|in:pdf,jpg,png,jpeg,doc,docx',
                'max_size_mb' => 'required|integer|min:1|max:50',
            ]);

            $document = ListDocumentRequired::create($validated);

            return response()->json([
                'message' => 'Document ajoute avec succes',
                'data' => $document
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Donnees invalides',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            Log::error('Erreur lors de la creation du document requis : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la creation du document'], 500);
        }
    }

    /**
     * Afficher un document requis
     */
    public function show($id)
    {
        try {
            $document = ListDocumentRequired::findOrFail($id);
            return response()->json(['data' => $document]);
        } catch (Exception $e) {
            Log::error('Erreur lors de la recuperation du document : ' . $e->getMessage());
            return response()->json(['message' => 'Document non trouve'], 404);
        }
    }

    /**
     * Mise a jour d'un document requis
     */
    public function update(Request $request, $id)
    {
        try {
            $document = ListDocumentRequired::findOrFail($id);

            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'guide' => 'nullable|string',
                'category' => 'sometimes|required|string|in:' . implode(',', ListDocumentRequired::getCategories()),
                'is_required' => 'boolean',
                'file_types' => 'sometimes|required|array|min:1',
                'file_types.*' => 'string|in:pdf,jpg,png,jpeg,doc,docx',
                'max_size_mb' => 'sometimes|required|integer|min:1|max:50',
                'is_active' => 'boolean',
            ]);

            $document->update($validated);

            return response()->json([
                'message' => 'Document mis a jour avec succes',
                'data' => $document
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Donnees invalides',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            Log::error('Erreur lors de la mise a jour du document : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la mise a jour du document'], 500);
        }
    }

    /**
     * Suppression d'un document requis
     */
    public function destroy($id)
    {
        try {
            $document = ListDocumentRequired::findOrFail($id);
            $document->delete();

            return response()->json(['message' => 'Document supprime avec succes']);
        } catch (Exception $e) {
            Log::error('Erreur lors de la suppression du document : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la suppression du document'], 500);
        }
    }

    /**
     * Basculer le statut actif/inactif
     */
    public function toggleActive($id)
    {
        try {
            $document = ListDocumentRequired::findOrFail($id);
            $document->is_active = !$document->is_active;
            $document->save();

            return response()->json([
                'message' => $document->is_active ? 'Document active' : 'Document desactive',
                'data' => $document
            ]);
        } catch (Exception $e) {
            Log::error('Erreur lors du basculement du statut : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors du basculement du statut'], 500);
        }
    }
}
