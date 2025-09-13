<?php

namespace AreiaLab\LoginNotifier\Listeners;

use Illuminate\Auth\Events\Login;
use AreiaLab\LoginNotifier\Models\LoginActivity;
use AreiaLab\LoginNotifier\Notifications\NewDeviceLoginNotification;

class SendLoginNotification
{
    public function handle(Login $event): void
    {
        $user = $event->user;
        $ip = request()->ip();
        $agent = request()->header('User-Agent');

        $alreadyKnown = LoginActivity::where('user_id', $user->id)
            ->where('ip_address', $ip)
            ->where('user_agent', $agent)
            ->exists();

        if (! $alreadyKnown) {
            LoginActivity::create([
                'user_id' => $user->id,
                'ip_address' => $ip,
                'user_agent' => $agent,
            ]);

            $user->notify(new NewDeviceLoginNotification($ip, $agent));
        }
    }
}
