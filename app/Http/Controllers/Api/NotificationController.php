<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NotificationRequest;
use App\Models\Notification;
use App\Http\Resources\NotificationResource;
use Illuminate\Support\Facades\Log;
use Exception;

class NotificationController extends Controller
{
    // Liste toutes les notifications
    public function index()
    {
        try {
            $notifications = Notification::with('user')->get();
            if ($notifications->isEmpty()) {
                return response()->json(['message' => 'Aucune notification trouvée'], 404);
            }
            Log::info('Data notification ', ['data' => $notifications]);
            return response()->json(['data' => NotificationResource::collection($notifications)]);
        } catch (Exception $e) {
            Log::error('Erreur lors de la récupération des notifications : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la récupération des notifications'], 500);
        }
    }

    // Créer une notification
    public function store(NotificationRequest $request)
    {
        try {
            $notification = Notification::create($request->validated());
            return response()->json(['message' => 'Notification créée avec succès', 'data' => new NotificationResource($notification)], 201);
        } catch (Exception $e) {
            Log::error('Erreur lors de la création de la notification : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la création de la notification'], 500);
        }
    }

    // Afficher une notification spécifique
    public function show($id)
    {
        try {
            $notification = Notification::with(['user'])->findOrFail($id);
            return response()->json(['data' => new NotificationResource($notification)]);
        } catch (Exception $e) {
            Log::error('Notification non trouvée : ' . $e->getMessage());
            return response()->json(['message' => 'Notification non trouvée'], 404);
        }
    }
    public function showUser($id)
    {
        try {
            $notification = Notification::with(['user'])->where('user_id', $id)->get();
            return response()->json(['data' => NotificationResource::collection($notification)]);
        } catch (Exception $e) {
            Log::error('Notification non trouvée : ' . $e->getMessage());
        }
    }
    // Mettre à jour une notification
    public function update(NotificationRequest $request, $id)
    {
        try {
            $notification = Notification::findOrFail($id);
            $notification->update($request->validated());
            return response()->json(['message' => 'Notification mise à jour avec succès'], 200);
        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour de la notification : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la mise à jour de la notification'], 500);
        }
    }

    // Supprimer une notification
    public function destroy($id)
    {
        try {
            $notification = Notification::findOrFail($id);
            $notification->delete();
            return response()->json(['message' => 'Notification supprimée avec succès'], 200);
        } catch (Exception $e) {
            Log::error('Erreur lors de la suppression de la notification : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la suppression de la notification'], 500);
        }
    }
}
