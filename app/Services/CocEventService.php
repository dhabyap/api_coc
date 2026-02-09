<?php

namespace App\Services;

use Carbon\Carbon;

class CocEventService
{
    /**
     * Get summary of all Clash of Clans recurring events.
     * Returns events with status (ACTIVE/UPCOMING) and countdown.
     */
    public function getEventsSummary(): array
    {
        $now = Carbon::now('UTC');

        return [
            $this->calculateRaidWeekend($now),
            $this->calculateCWL($now),
            $this->calculateClanGames($now),
            $this->calculateSeasonEnd($now),
            $this->calculateTraderRefresh($now),
        ];
    }

    /**
     * Calculate Raid Weekend status and countdown.
     * Active: Friday 07:00 UTC → Monday 07:00 UTC
     */
    private function calculateRaidWeekend(Carbon $now): array
    {
        $dayOfWeek = $now->dayOfWeek; // 0=Sunday, 5=Friday, 1=Monday
        $currentHour = $now->hour;

        // Find the start of this week's Raid Weekend (Friday 07:00)
        $raidStart = $now->copy()->startOfWeek()->addDays(4)->setTime(7, 0, 0); // Friday 07:00

        // If we're before Friday 07:00 this week, use this week's Friday
        // If we're after Monday 07:00, use next week's Friday
        if ($now->lt($raidStart)) {
            // Before this week's raid - use this week
            $nextStart = $raidStart;
        } elseif ($dayOfWeek === 1 && $currentHour >= 7) {
            // Monday after 07:00 - raid ended, next is next Friday
            $nextStart = $raidStart->copy()->addWeek();
        } elseif ($dayOfWeek > 1 || ($dayOfWeek === 1 && $currentHour < 7)) {
            // Tuesday-Thursday or Monday before 07:00 - next Friday
            $nextStart = $raidStart->copy()->addWeek();
        } else {
            // Currently in raid window (Friday 07:00 - Monday 07:00)
            $nextStart = $raidStart;
        }

        $raidEnd = $nextStart->copy()->addDays(3); // Monday 07:00

        if ($now->gte($nextStart) && $now->lt($raidEnd)) {
            // ACTIVE
            $secondsLeft = $now->diffInSeconds($raidEnd, false);
            return [
                'key' => 'raid_weekend',
                'name' => 'Raid Weekend',
                'status' => 'ACTIVE',
                'countdown' => $this->formatCountdown(abs($secondsLeft)),
            ];
        } else {
            // UPCOMING
            $secondsUntil = $now->diffInSeconds($nextStart, false);
            return [
                'key' => 'raid_weekend',
                'name' => 'Raid Weekend',
                'status' => 'UPCOMING',
                'countdown' => $this->formatCountdown(abs($secondsUntil)),
            ];
        }
    }

    /**
     * Calculate CWL (Clan War League) status and countdown.
     * Active: 1st-10th of each month
     */
    private function calculateCWL(Carbon $now): array
    {
        $currentDay = $now->day;

        if ($currentDay >= 1 && $currentDay <= 10) {
            // ACTIVE - ends at end of 10th
            $cwlEnd = $now->copy()->setDay(10)->endOfDay();
            $secondsLeft = $now->diffInSeconds($cwlEnd, false);

            return [
                'key' => 'cwl',
                'name' => 'CWL',
                'status' => 'ACTIVE',
                'countdown' => $this->formatCountdown(abs($secondsLeft)),
            ];
        } else {
            // UPCOMING - starts on 1st of next month
            $cwlStart = $now->copy()->addMonth()->setDay(1)->startOfDay();
            $secondsUntil = $now->diffInSeconds($cwlStart, false);

            return [
                'key' => 'cwl',
                'name' => 'CWL',
                'status' => 'UPCOMING',
                'countdown' => $this->formatCountdown(abs($secondsUntil)),
            ];
        }
    }

    /**
     * Calculate Clan Games status and countdown.
     * Active: 22nd-28th of each month
     */
    private function calculateClanGames(Carbon $now): array
    {
        $currentDay = $now->day;

        if ($currentDay >= 22 && $currentDay <= 28) {
            // ACTIVE - ends at end of 28th
            $gamesEnd = $now->copy()->setDay(28)->endOfDay();
            $secondsLeft = $now->diffInSeconds($gamesEnd, false);

            return [
                'key' => 'clan_games',
                'name' => 'Clan Games',
                'status' => 'ACTIVE',
                'countdown' => $this->formatCountdown(abs($secondsLeft)),
            ];
        } else {
            // UPCOMING - starts on 22nd
            if ($currentDay < 22) {
                // This month's 22nd
                $gamesStart = $now->copy()->setDay(22)->startOfDay();
            } else {
                // Next month's 22nd
                $gamesStart = $now->copy()->addMonth()->setDay(22)->startOfDay();
            }

            $secondsUntil = $now->diffInSeconds($gamesStart, false);

            return [
                'key' => 'clan_games',
                'name' => 'Clan Games',
                'status' => 'UPCOMING',
                'countdown' => $this->formatCountdown(abs($secondsUntil)),
            ];
        }
    }

    /**
     * Calculate Season End / League Reset status and countdown.
     * Active: Last day of month
     */
    private function calculateSeasonEnd(Carbon $now): array
    {
        $lastDayOfMonth = $now->copy()->endOfMonth()->day;
        $currentDay = $now->day;

        if ($currentDay === $lastDayOfMonth) {
            // ACTIVE - ends at end of today
            $seasonEnd = $now->copy()->endOfDay();
            $secondsLeft = $now->diffInSeconds($seasonEnd, false);

            return [
                'key' => 'season_end',
                'name' => 'Season End',
                'status' => 'ACTIVE',
                'countdown' => $this->formatCountdown(abs($secondsLeft)),
            ];
        } else {
            // UPCOMING - next month's last day
            $nextSeasonEnd = $now->copy()->endOfMonth()->startOfDay();
            $secondsUntil = $now->diffInSeconds($nextSeasonEnd, false);

            return [
                'key' => 'season_end',
                'name' => 'Season End',
                'status' => 'UPCOMING',
                'countdown' => $this->formatCountdown(abs($secondsUntil)),
            ];
        }
    }

    /**
     * Calculate Trader Refresh countdown.
     * Refreshes every 24 hours (rolling).
     * Since we don't track the exact refresh time, we'll show a generic 24h cycle.
     */
    private function calculateTraderRefresh(Carbon $now): array
    {
        // Trader refreshes at a specific time daily (let's assume 08:00 UTC)
        $todayRefresh = $now->copy()->setTime(8, 0, 0);

        if ($now->lt($todayRefresh)) {
            // Today's refresh hasn't happened yet
            $nextRefresh = $todayRefresh;
        } else {
            // Today's refresh already happened, next is tomorrow
            $nextRefresh = $todayRefresh->copy()->addDay();
        }

        $secondsUntil = $now->diffInSeconds($nextRefresh, false);

        return [
            'key' => 'trader_refresh',
            'name' => 'Trader Refresh',
            'status' => 'UPCOMING',
            'countdown' => $this->formatCountdown(abs($secondsUntil)),
        ];
    }

    /**
     * Format seconds into "Xd Xh Xm" format.
     */
    private function formatCountdown(int $seconds): string
    {
        $days = floor($seconds / 86400);
        $hours = floor(($seconds % 86400) / 3600);
        $minutes = floor(($seconds % 3600) / 60);

        $parts = [];

        if ($days > 0) {
            $parts[] = $days . 'd';
        }

        if ($hours > 0 || $days > 0) {
            $parts[] = $hours . 'h';
        }

        $parts[] = $minutes . 'm';

        return implode(' ', $parts);
    }
}
