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
        $this->mergeConfigFrom(__DIR__ . '/../config/login-notifier.php', 'login-notifier');

        $this->app->singleton('login-histories', function ($app) {
            return new LoginHistoryService();
        });
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Register the login event listener
        Event::listen(Login::class, [LoginListener::class, 'handle']);

        // Publish config file
        $this->publishes([
            __DIR__ . '/../config/login-notifier.php' => config_path('login-notifier.php'),
        ], 'login-notifier-config');

        // Publish migrations
        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations'),
        ], 'login-notifier-migrations');
    }
}
