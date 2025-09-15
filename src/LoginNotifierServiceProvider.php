<?php

namespace AreiaLab\LoginNotifier;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Events\Login;
use AreiaLab\LoginNotifier\Listeners\LoginListener;
use Illuminate\Support\Facades\Event;

class LoginNotifierServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/config/login-notifier.php', 'login-notifier');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        Event::listen(Login::class, LoginListener::class);

        $this->publishes([
            __DIR__ . '/config/login-notifier.php' => config_path('login-notifier.php'),
            __DIR__ . '/../database/migrations/' => database_path('migrations'),
        ], 'login-notifier-assets');
    }
}
