<?php

namespace AreiaLab\LoginNotifier;

use AreiaLab\LoginNotifier\Models\LoginHistory;
use Illuminate\Database\Eloquent\Collection;

class LoginHistoryService
{
    /**
     * Get all login histories.
     */
    public function all(): Collection
    {
        return LoginHistory::latest()->get();
    }

    /**
     * Get login histories for a specific user.
     */
    public function forUser(int $userId): Collection
    {
        return LoginHistory::where('user_id', $userId)->latest()->get();
    }

    /**
     * Get recent logins (e.g., last X records or new logins).
     */
    public function recent(int $limit = 10): Collection
    {
        return LoginHistory::latest()->limit($limit)->get();
    }

    /**
     * Get last login for a specific user.
     */
    public function lastForUser(int $userId): ?LoginHistory
    {
        return LoginHistory::where('user_id', $userId)->latest()->first();
    }
}
