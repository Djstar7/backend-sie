<?php

namespace App\Providers;

use App\Events\UserActionEvent;
use App\Listeners\SendUserActionNotifications;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserActionEvent::class => [
            SendUserActionNotifications::class,
        ],
    ];
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
