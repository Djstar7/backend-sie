<?php

namespace App\Http\Controllers\API;

use App\Events\UserActionEvent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use Illuminate\Support\Facades\Auth;
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

        $user = Auth::user();

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
        return response()->json(['data' => NotificationResource::collection(Auth::user()->notifications)]);
    }

    // Non lues
    public function unread(Request $request)
    {
        Log::info('unread', [Auth::user()->unreadNotifications->count()]);
        return ['data' => Auth::user()->unreadNotifications->count()];
    }


    // Marquer une notification comme lue
    public function markAsRead(string $id)
    {
        $notification = Auth::user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }

        return response()->json(["status" => "done"]);
    }
    public function markUnRead(string $id)
    {
        $notification = Auth::user()->notifications()->find($id);
        if ($notification) {
            $notification->update(['read_at' => null]);
        }

        return response()->json(["status" => "done"]);
    }

    // Tout marquer comme lu
    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(["status" => "done"]);
    }
}
