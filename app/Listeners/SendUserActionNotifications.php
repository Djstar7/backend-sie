<?php

namespace App\Listeners;

use App\Events\UserActionEvent;
use App\Notifications\UserActionNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
        $user = $event->user;

        $user->notify(new UserActionNotification($event->payload));
    }
}
