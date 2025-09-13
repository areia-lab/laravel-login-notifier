<?php

namespace AreiaLab\LoginNotifier;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use AreiaLab\LoginNotifier\Listeners\SendLoginNotification;

class LoginNotifierServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        Event::listen(Login::class, SendLoginNotification::class);

        // Publish migrations
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'notifier-migrations');
    }
}
