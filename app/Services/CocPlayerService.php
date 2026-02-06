<?php

namespace App\Services;

use App\Models\CocPlayer;
use Carbon\Carbon;

class CocPlayerService
{
    protected CocService $cocService;

    public function __construct(CocService $cocService)
    {
        $this->cocService = $cocService;
    }

    /**
     * Get player data by tag with 24-hour caching logic.
     */
    public function getPlayerByTag(string $tag): array
    {
        // Normalize tag: remove # and make uppercase
        $tag = strtoupper(str_replace('#', '', $tag));
        $fullTag = '#' . $tag;

        $cachedPlayer = CocPlayer::where('player_tag', $fullTag)->first();

        if ($cachedPlayer) {
            $hoursSinceFetch = Carbon::now()->diffInHours($cachedPlayer->last_fetched_at);

            if ($hoursSinceFetch < 24) {
                return [
                    'success' => true,
                    'data' => $cachedPlayer->payload,
                    'source' => 'cache',
                    'last_fetched_at' => $cachedPlayer->last_fetched_at,
                ];
            }
        }

        // Fetch from API if not cached or expired
        $result = $this->cocService->getPlayer($fullTag);

        if ($result['success']) {
            $playerData = $result['data'];

            CocPlayer::updateOrCreate(
                ['player_tag' => $fullTag],
                [
                    'player_name' => $playerData['name'],
                    'town_hall_level' => $playerData['townHallLevel'],
                    'payload' => $playerData,
                    'last_fetched_at' => Carbon::now(),
                ]
            );

            return [
                'success' => true,
                'data' => $playerData,
                'source' => 'api',
                'last_fetched_at' => Carbon::now(),
            ];
        }

        return $result;
    }
}
