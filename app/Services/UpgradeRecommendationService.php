<?php

namespace App\Services;

class UpgradeRecommendationService
{
    /**
     * Get upgrade recommendations based on player payload.
     */
    public function getRecommendations(array $player): array
    {
        $recommendations = [];
        $th = $player['townHallLevel'] ?? 0;

        // 1. Hero Recommendations
        foreach ($player['heroes'] ?? [] as $hero) {
            if ($hero['level'] < $hero['maxLevel']) {
                $recommendations[] = [
                    'title' => "Upgrade " . $hero['name'],
                    'reason' => "Increase your offensive power. Current Lv. {$hero['level']}, Max Lv. {$hero['maxLevel']}",
                    'priority' => 'High',
                ];
            }
        }

        // 2. Core Defense & Infrastructure (General logic based on TH)
        if ($th >= 7) {
            $recommendations[] = [
                'title' => "Laboratory & Clan Castle",
                'reason' => "Always prioritize troops and CC capacity after TH upgrade.",
                'priority' => 'High',
            ];
        }

        if ($th >= 11) {
            $recommendations[] = [
                'title' => "Eagle Artillery",
                'reason' => "Essential for defending against large armies.",
                'priority' => 'High',
            ];
        }

        if ($th >= 10) {
            $recommendations[] = [
                'title' => "Inferno Towers",
                'reason' => "Key defensive structure for multi or single target damage.",
                'priority' => 'Medium',
            ];
        }

        if ($th >= 13) {
            $recommendations[] = [
                'title' => "Scattershot",
                'reason' => "Provides powerful area damage against groups.",
                'priority' => 'High',
            ];
        }

        // 3. Resource Recommendations
        $recommendations[] = [
            'title' => "Gold & Elixir Storages",
            'reason' => "Ensure you have enough capacity for expensive upgrades.",
            'priority' => 'Medium',
        ];

        return array_slice($recommendations, 0, 5); // Return top 5
    }
}
