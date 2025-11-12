<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LogRequest;
use App\Http\Requests\LogStoreRequest;
use App\Http\Requests\LogUpdateRequest;
use App\Http\Resources\LogResource;
use App\Models\Log;
use Illuminate\Support\Facades\Log as LogFacade;
use Exception;

class LogController extends Controller
{
    // Liste tous les logs
    public function index()
    {
        try {
            $logs = Log::with('user')->get();

            if ($logs->isEmpty()) {
                return response()->json(['message' => 'Aucun log trouvé'], 404);
            }

            // Filtrer les données via la ressource
            return LogResource::collection($logs);
        } catch (Exception $e) {
            LogFacade::error('Erreur lors de la récupération des logs : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la récupération des logs'], 500);
        }
    }

    // Créer un log
    public function store(LogRequest $request)
    {
        try {
            $log = Log::create($request->validated());
            return response()->json(['message' => 'Log enregistré avec succès', 'data' => new LogResource($log)], 201);
        } catch (Exception $e) {
            LogFacade::error('Erreur lors de la création du log : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de l\'enregistrement du log'], 500);
        }
    }

    // Afficher un log
    public function show($id)
    {
        try {
            $log = Log::with('user')->findOrFail($id);
            return response()->json(new LogResource($log));
        } catch (Exception $e) {
            LogFacade::error('Log non trouvé : ' . $e->getMessage());
            return response()->json(['message' => 'Log non trouvé'], 404);
        }
    }

    // Mettre à jour un log
    public function update(LogRequest $request, $id)
    {
        try {
            $log = Log::findOrFail($id);
            $log->update($request->validated());
            return response()->json(['message' => 'Log mis à jour avec succès'], 200);
        } catch (Exception $e) {
            LogFacade::error('Erreur lors de la mise à jour du log : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la mise à jour du log'], 500);
        }
    }

    // Supprimer un log
    public function destroy($id)
    {
        try {
            $log = Log::findOrFail($id);
            $log->delete();
            return response()->json(['message' => 'Log supprimé avec succès'], 200);
        } catch (Exception $e) {
            LogFacade::error('Erreur lors de la suppression du log : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la suppression du log'], 500);
        }
    }
}
