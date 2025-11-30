<?php

namespace App\Http\Controllers\API;

use App\Events\UserActionEvent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
    // Déclenche une notification
    public function sendActionNotification(Request $request)
    {
        $request->validate([
            "type" => "required|string",
            "message" => "required|string",
            "meta" => "nullable|array"
        ]);

        $user = $request->user();

        UserActionEvent::dispatch($user, [
            "type" => $request->type,
            "message" => $request->message,
            "meta" => $request->meta
        ]);

        return response()->json([
            "status" => "success",
            "message" => "Notification envoyée sans ralentir l'application."
        ], 200);
    }

    // Récupérer notifications
    public function index(Request $request)
    {
        return response()->json(['data' => NotificationResource::collection($request->user()->notifications)]);
    }

    // Non lues
    public function unread(Request $request)
    {
        return response()->json(NotificationResource::collection($request->user()->unreadNotifications));
    }

    // Marquer une notification comme lue
    public function markAsRead(Request $request, $id)
    {
        $notification = $request->user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }

        return response()->json(["status" => "done"]);
    }

    // Tout marquer comme lu
    public function markAllRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();
        return response()->json(["status" => "done"]);
    }
}
