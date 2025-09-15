<?php

namespace AreiaLab\LoginNotifier\Listeners;

use Illuminate\Auth\Events\Login;
use AreiaLab\LoginNotifier\Models\LoginHistory;
use AreiaLab\LoginNotifier\Notifications\UserLoginAlert;
use AreiaLab\LoginNotifier\Notifications\AdminLoginAlert;
use Illuminate\Support\Facades\Notification;
use Stevebauman\Location\Facades\Location;
use Jenssegers\Agent\Agent;

class LoginListener
{
    public function handle(Login $event)
    {
        $user = $event->user;
        $ip = request()->ip();
        $agent = new Agent();
        $device = $agent->device();
        $platform = $agent->platform();
        $browser = $agent->browser();

        $location = @Location::get($ip);
        $geo = $location ? $location->cityName . ', ' . $location->countryName : null;

        $lastLogin = LoginHistory::where('user_id', $user->id)->latest()->first();

        $isNewDevice = !$lastLogin || $lastLogin->ip_address !== $ip;

        $loginHistory = LoginHistory::create([
            'user_id' => $user->id,
            'ip_address' => $ip,
            'device' => $device,
            'platform' => $platform,
            'browser' => $browser,
            'geo_location' => $geo,
        ]);

        if ($isNewDevice && config('login-notifier.notify_user')) {
            $user->notify(new UserLoginAlert($loginHistory));
        }

        if (config('login-notifier.notify_admin') && config('login-notifier.admin_email')) {
            Notification::route('mail', config('login-notifier.admin_email'))
                ->notify(new AdminLoginAlert($user, $loginHistory));
        }
    }
}
