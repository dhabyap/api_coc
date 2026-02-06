<?php

namespace App\Services;

use App\Models\CocPlayer;
use Illuminate\Support\Facades\Cache;

class GlobalStatsService
{
    /**
     * Get anonymous global statistics and random recent analyses.
     */
    public function getGlobalStats(): array
    {
        // We use a shorter cache or no cache for the "Recent" part to keep it fresh
        $stats = Cache::remember('coc_global_stats_basic', 3600, function () {
            $players = CocPlayer::all();
            $total = $players->count();

            if ($total === 0) {
                return [
                    'totalAccounts' => 0,
                    'warReadyCount' => 0,
                    'topRecommendedTroop' => 'N/A',
                ];
            }

            $warReady = 0;
            $troopCounts = [];

            foreach ($players as $p) {
                $payload = $p->payload;
                if (($payload['townHallLevel'] ?? 1) >= 11 && ($payload['heroes'][0]['level'] ?? 0) >= 40) {
                    $warReady++;
                }

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
            ];
        });

        // Get 4 random recent analyses
        $recentAnalyses = CocPlayer::latest()
            ->limit(10)
            ->get()
            ->shuffle()
            ->take(4)
            ->map(function ($p) {
                return [
                    'name' => $p->player_name,
                    'tag' => $p->player_tag,
                    'th' => $p->town_hall_level,
                    'time' => $p->updated_at->diffForHumans()
                ];
            })->toArray();

        $stats['recentAnalyses'] = $recentAnalyses;

        return $stats;
    }
}
