<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FaqChabotRequest;
use App\Http\Resources\FaqChabotResource;
use App\Models\FaqChabot;
use Illuminate\Support\Facades\Log;
use Exception;

class FaqChabotController extends Controller
{
    public function index()
    {
        try {
            $faqs = FaqChabot::all();

            if ($faqs->isEmpty()) {
                return response()->json(['message' => 'Aucune FAQ trouvée'], 404);
            }

            // Filtrer les données via la ressource
            return FaqChabotResource::collection($faqs);
        } catch (Exception $e) {
            Log::error('Erreur lors de la récupération des FAQ : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la récupération des FAQ'], 500);
        }
    }

    public function store(FaqChabotRequest $request)
    {
        try {
            $faqChat = FaqChabot::create($request->validated());
            return response()->json(['message' => 'FAQ créée avec succès', 'data' => new FaqChabotResource($faqChat)], 201);
        } catch (Exception $e) {
            Log::error('Erreur lors de la création de la FAQ : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la création de la FAQ'], 500);
        }
    }

    public function show($id)
    {
        try {
            $faq = FaqChabot::findOrFail($id);
            return response()->json(new FaqChabotResource($faq));
        } catch (Exception $e) {
            Log::error('FAQ non trouvée : ' . $e->getMessage());
            return response()->json(['message' => 'FAQ non trouvée'], 404);
        }
    }

    public function update(FaqChabotRequest $request, $id)
    {
        try {
            $faq = FaqChabot::findOrFail($id);
            $faq->update($request->validated());
            return response()->json(['message' => 'FAQ mise à jour avec succès'], 200);
        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour de la FAQ : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la mise à jour de la FAQ'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $faq = FaqChabot::findOrFail($id);
            $faq->delete();
            return response()->json(['message' => 'FAQ supprimée avec succès'], 200);
        } catch (Exception $e) {
            Log::error('Erreur lors de la suppression de la FAQ : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la suppression de la FAQ'], 500);
        }
    }
}
