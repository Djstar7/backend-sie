<?php

namespace App\Http\Controllers\Api;

use App\Events\UserActionEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\MessageRequest;
use App\Models\Message;
use App\Http\Resources\MessageResource;
use App\Models\User;
use App\Models\VisaRequest;
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

    // Créer un message (client → agents ou agent → client)
    public function store(MessageRequest $request)
    {
        try {
            $validated = $request->validated();
            $message = Message::create($validated);

            // Notification si AGENT écrit → notifier le CLIENT
            if (Auth::user()->hasRole('agent')) {
                $visaRequest = VisaRequest::find($validated['visa_request_id']);

                UserActionEvent::dispatch(
                    User::find($visaRequest['user_id']),
                    [
                        "type" => "Message",
                        "message" => "$message->content",
                        "link" => "/custom/chat/$message->visa_request_id"
                    ]
                );
            } else {
                // Notification si CLIENT écrit → notifier TOUS LES AGENTS
                $agents = User::whereHas('roles', function ($q) {
                    $q->where('name', 'agent');
                })->get();

                foreach ($agents as $agent) {
                    UserActionEvent::dispatch(
                        $agent,
                        [
                            "type" => "Message",
                            "author" => User::find($message->user_id)->name,
                            "message" => "$message->content",
                            "link" => "/agent/chat/{$validated['user_id']}/$message->visa_request_id"
                        ]
                    );
                }
            }

            return response()->json([
                'message' => 'Message créé avec succès',
                'data' => new MessageResource($message)
            ], 201);
        } catch (Exception $e) {
            Log::error('Erreur lors de la création du message : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la création du message'], 500);
        }
    }

    // Affiche un message
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

    // Messages filtrés + marquage "read"
    public function showByVisaRequest($customId, $visaRequestId)
    {
        try {
            $messages = Message::where('visa_request_id', $visaRequestId)
                ->with('user', 'visaRequest')
                ->get();

            if ($messages->isEmpty()) {
                return response()->json(['message' => 'Aucun message trouvé'], 404);
            }

            // FILTRAGE
            $agentMessages = $messages->filter(
                fn($msg) =>
                $msg->user->roles->contains('name', 'agent')
            )->values();

            $customMessages = $messages->filter(
                fn($msg) =>
                $msg->visaRequest &&
                    $msg->visaRequest->user_id == $customId &&
                    $msg->user_id == $customId
            )->values();

            if (Auth::user()->hasRole('agent')) {
                foreach ($customMessages as $msg) {
                    if ($msg->status !== 'read') {
                        $msg->update(['status' => 'read']);
                    }
                }
            } else {
                foreach ($agentMessages as $msg) {
                    if ($msg->status !== 'read') {
                        $msg->update(['status' => 'read']);
                    }
                }
            }

            return response()->json([
                'agentMessages' => MessageResource::collection($agentMessages),
                'customMessages' => MessageResource::collection($customMessages),
            ]);
        } catch (Exception $e) {
            Log::error('Erreur lors de la récupération des messages : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la récupération des messages'], 500);
        }
    }

    // Mise à jour
    public function update(MessageRequest $request, $id)
    {
        try {
            $message = Message::findOrFail($id);
            $message->update($request->validated());
            if ($message->status === 'read') {
                $message->update(['status' => 'sent']);
            }
            return response()->json(['message' => 'Message mis à jour avec succès'], 200);
        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour du message : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la mise à jour du message'], 500);
        }
    }

    // Supprimer
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
