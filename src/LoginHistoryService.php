<?php

namespace AreiaLab\LoginNotifier;

use AreiaLab\LoginNotifier\Models\LoginHistory;
use Illuminate\Database\Eloquent\Collection;

class LoginHistoryService
{
    /**
     * Get all login histories, latest first.
     */
    public function getAllHistories(): Collection
    {
        return LoginHistory::query()
            ->latest()
            ->get();
    }

    /**
     * Get all login histories for a specific user.
     *
     * @param int $userId
     * @param int|null $limit Optional number of records
     */
    public function getUserHistories(int $userId, ?int $limit = null): Collection
    {
        $query = LoginHistory::where('user_id', $userId)
            ->latest();

        return $limit ? $query->limit($limit)->get() : $query->get();
    }

    /**
     * Get the most recent login histories.
     *
     * @param int $limit Number of records to retrieve
     */
    public function getRecentHistories(int $limit = 10): Collection
    {
        return LoginHistory::query()
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get the last login of a specific user.
     */
    public function getLastLoginForUser(int $userId): ?LoginHistory
    {
        return LoginHistory::where('user_id', $userId)
            ->latest()
            ->first();
    }

    /**
     * Get logins from new devices or IPs for a specific user.
     *
     * @param int $userId
     */
    public function getNewDeviceLoginsForUser(int $userId): Collection
    {
        $lastLogin = $this->getLastLoginForUser($userId);

        if (!$lastLogin) {
            return $this->getUserHistories($userId);
        }

        return LoginHistory::where('user_id', $userId)
            ->where(function ($query) use ($lastLogin) {
                $query->where('ip_address', '!=', $lastLogin->ip_address)
                    ->orWhere('device', '!=', $lastLogin->device)
                    ->orWhere('platform', '!=', $lastLogin->platform)
                    ->orWhere('browser', '!=', $lastLogin->browser);
            })
            ->latest()
            ->get();
    }
}
