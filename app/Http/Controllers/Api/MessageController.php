<?php

namespace App\Http\Controllers\Api;

use App\Events\UserActionEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\MessageRequest;
use App\Models\Message;
use App\Http\Resources\MessageResource;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // Liste tous les messages
    public function index()
    {
        try {
            $messages = Message::with(['user', 'visaRequest'])->get();
            if ($messages->isEmpty()) {
                return response()->json(['message' => 'Aucun message trouvé'], 404);
            }
            return MessageResource::collection($messages);
        } catch (Exception $e) {
            Log::error('Erreur lors de la récupération des messages : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la récupération des messages'], 500);
        }
    }

    // Créer un message
    public function store(MessageRequest $request)
    {
        try {
            $validated = $request->validated();
            $message = Message::create($validated);
            if (Auth::user()->hasRole('agent')) {
                UserActionEvent::dispatch(User::find($validated['user_id']), [
                    "type" => "Message",
                    "message" => "Nouveaux messages recus"
                ]);
            } else {
                $agents = User::whereHas('roles', function ($query) {
                    $query->where('name', 'agent');
                })->get();
                foreach ($agents as $agent) {
                    Log::info('agent', ['agent' => $agents]);
                    UserActionEvent::dispatch($agent, [
                        "type" => "Message",
                        "message" => "Nouveaux messages recus"
                    ]);
                }
            }
            return response()->json(['message' => 'Message créé avec succès', 'data' => new MessageResource($message)], 201);
        } catch (Exception $e) {
            Log::error('Erreur lors de la création du message : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la création du message'], 500);
        }
    }

    // Afficher un message spécifique
    public function show($id)
    {
        try {
            $message = Message::with(['user', 'visaRequest'])->findOrFail($id);
            return response()->json(new MessageResource($message));
        } catch (Exception $e) {
            Log::error('Message non trouvé : ' . $e->getMessage());
            return response()->json(['message' => 'Message non trouvé'], 404);
        }
    }

    // Afficher les messages par demande de visa et filtrage agent/client
    public function showByVisaRequest($customId, $visaRequestId)
    {
        try {
            $messages = Message::where('visa_request_id', $visaRequestId)
                ->with('user', 'visaRequest')
                ->get();

            if ($messages->isEmpty()) {
                return response()->json(['message' => 'Aucun message trouvé'], 404);
            }

            $agentMessages = $messages->filter(fn($msg) => $msg->user->roles->contains('name', 'agent'))->values();
            $customMessages = $messages->filter(fn($msg) => $msg->visaRequest && $msg->visaRequest->user_id == $customId && $msg->user_id == $customId)->values();
            return response()->json([
                'message' => 'Liste des messages récupérée avec succès',
                'agentMessages' => MessageResource::collection($agentMessages),
                'customMessages' => MessageResource::collection($customMessages),
            ]);
        } catch (Exception $e) {
            Log::error('Erreur lors de la récupération des messages : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la récupération des messages'], 500);
        }
    }

    // Mettre à jour un message
    public function update(MessageRequest $request, $id)
    {
        try {
            $message = Message::findOrFail($id);
            $message->update($request->validated());
            return response()->json(['message' => 'Message mis à jour avec succès'], 200);
        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour du message : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la mise à jour du message'], 500);
        }
    }

    // Supprimer un message
    public function destroy($id)
    {
        try {
            $message = Message::findOrFail($id);
            $message->delete();
            return response()->json(['message' => 'Message supprimé avec succès'], 200);
        } catch (Exception $e) {
            Log::error('Erreur lors de la suppression du message : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la suppression du message'], 500);
        }
    }
}
