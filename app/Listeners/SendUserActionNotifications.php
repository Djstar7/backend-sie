<?php

namespace App\Listeners;

use App\Events\UserActionEvent;
use App\Models\User;
use App\Notifications\UserActionNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendUserActionNotifications
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */

    public function handle(UserActionEvent $event)
    {
        $userData = $event->user;
        // Si $userData est déjà un User (parfois Laravel fait ça)
        if ($userData instanceof User) {
            $user = $userData;
        }

        // Si c’est un tableau contenant un User sérialisé
        elseif (is_array($userData) && count($userData) > 0) {
            $first = reset($userData);

            // cas où on a ["App\Models\User" => ["id" => "..."]]
            if (is_array($first)) {
                $userId = $first['id'] ?? null;
            } else {
                $userId = $userData['id'] ?? null;
            }

            // Si $userData est déjà un User (parfois

            $user = $userId ? User::find($userId) : null;
        } else {
            Log::error("UserActionEvent user invalid", [$userData]);
            return;
        }

        if ($user) {
            $user->notify(new UserActionNotification($event->payload));
        } else {
            Log::error("Impossible de récupérer le user dans SendUserActionNotifications", [$userData]);
        }
    }
}
