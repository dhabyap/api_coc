<?php

namespace App\Services;

use App\Models\CocPlayer;
use Illuminate\Support\Facades\Cache;

class GlobalStatsService
{
    /**
     * Get anonymous global statistics.
     */
    public function getGlobalStats(): array
    {
        return Cache::remember('coc_global_stats', 3600, function () {
            $players = CocPlayer::all();
            $total = $players->count();

            if ($total === 0) {
                return [
                    'totalAccounts' => 0,
                    'warReadyCount' => 0,
                    'topRecommendedTroop' => 'N/A',
                ];
            }

            // Simple aggregation for "Live" feel
            $warReady = 0;
            $troopCounts = [];

            foreach ($players as $p) {
                $payload = $p->payload;
                // Simple heuristic for "Ready" in global context
                if (($payload['townHallLevel'] ?? 1) >= 11 && ($payload['heroes'][0]['level'] ?? 0) >= 40) {
                    $warReady++;
                }

                // Count troops for "Most Recommended" (mock logic for demo)
                foreach ($payload['troops'] ?? [] as $t) {
                    if ($t['level'] < $t['maxLevel']) {
                        $troopCounts[$t['name']] = ($troopCounts[$t['name']] ?? 0) + 1;
                    }
                }
            }

            arsort($troopCounts);
            $topTroop = array_key_first($troopCounts) ?: 'Giant';

            return [
                'totalAccounts' => $total,
                'warReadyCount' => $warReady,
                'topRecommendedTroop' => $topTroop,
                'lastUpdate' => now()->toDateTimeString(),
            ];
        });
    }
}
