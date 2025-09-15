<?php

namespace AreiaLab\LoginNotifier\Listeners;

use Illuminate\Auth\Events\Login;
use AreiaLab\LoginNotifier\Models\LoginHistory;
use AreiaLab\LoginNotifier\Notifications\UserLoginAlert;
use AreiaLab\LoginNotifier\Notifications\AdminLoginAlert;
use Illuminate\Support\Facades\Notification;
use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;

class LoginListener
{
    public function handle(Login $event): void
    {
        $user = $event->user;
        $ip = request()->ip();

        // Gather device/browser/platform info
        [$device, $platform, $browser] = $this->getAgentDetails();

        // Try resolving geo location safely
        $geo = $this->getGeoLocation($ip);

        // Determine if it's a new device or location
        $lastLogin = $this->getLastLogin($user->id);
        $isNewDevice = $this->isNewDevice($lastLogin, $ip, $device, $platform, $browser);

        // Store login history
        $loginHistory = $this->storeLoginHistory($user->id, $ip, $device, $platform, $browser, $geo);

        // Send notifications
        $this->notifyUserIfNeeded($user, $loginHistory, $isNewDevice);
        $this->notifyAdminIfNeeded($user, $loginHistory);
    }

    /**
     * Get device, platform, and browser from user agent.
     */
    protected function getAgentDetails(): array
    {
        $agent = new Agent();
        return [
            $agent->device() ?? 'Unknown Device',
            $agent->platform() ?? 'Unknown Platform',
            $agent->browser() ?? 'Unknown Browser',
        ];
    }

    /**
     * Resolve the geolocation for an IP address.
     */
    protected function getGeoLocation(string $ip): ?string
    {
        try {
            $location = Location::get($ip);
            return $location ? trim(($location->cityName ?? '') . ', ' . ($location->countryName ?? '')) : null;
        } catch (\Throwable $e) {
            return null; // fail gracefully in production
        }
    }

    /**
     * Fetch the last login entry for a user.
     */
    protected function getLastLogin(int $userId): ?LoginHistory
    {
        return LoginHistory::where('user_id', $userId)->latest()->first();
    }

    /**
     * Check if the login is from a new device or location.
     */
    protected function isNewDevice(?LoginHistory $lastLogin, string $ip, string $device, string $platform, string $browser): bool
    {
        if (!$lastLogin) {
            return true;
        }

        return $lastLogin->ip_address !== $ip
            || $lastLogin->device !== $device
            || $lastLogin->platform !== $platform
            || $lastLogin->browser !== $browser;
    }

    /**
     * Store a new login history record.
     */
    protected function storeLoginHistory(int $userId, string $ip, string $device, string $platform, string $browser, ?string $geo): LoginHistory
    {
        return LoginHistory::create([
            'user_id'     => $userId,
            'ip_address'  => $ip,
            'device'      => $device,
            'platform'    => $platform,
            'browser'     => $browser,
            'geo_location' => $geo,
        ]);
    }

    /**
     * Notify the user if enabled and login is from a new device.
     */
    protected function notifyUserIfNeeded($user, LoginHistory $loginHistory, bool $isNewDevice): void
    {
        if ($isNewDevice && config('login-notifier.notify_user', true)) {
            $user->notify(new UserLoginAlert($loginHistory));
        }
    }

    /**
     * Notify the admin if enabled in config.
     */
    protected function notifyAdminIfNeeded($user, LoginHistory $loginHistory): void
    {
        $adminEmail = config('login-notifier.admin_email');

        if (config('login-notifier.notify_admin', false) && $adminEmail) {
            Notification::route('mail', $adminEmail)
                ->notify(new AdminLoginAlert($user, $loginHistory));
        }
    }
}
