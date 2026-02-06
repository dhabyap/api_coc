<?php

namespace App\Services;

class WarReadinessService
{
    /**
     * Determine the war readiness status of a player.
     * 
     * @param array $player
     * @param array $heroData
     * @param array $troopData
     * @return array
     */
    public function calculateStatus(array $player, array $heroData, array $troopData): array
    {
        $th = $player['townHallLevel'] ?? 1;
        $warPref = ($player['warPreference'] ?? 'out') === 'in';

        $heroesReady = $heroData['averageProgress'] >= 80;
        $troopsReady = $troopData['readinessScore'] >= 75;

        // Specific rules for TH levels
        if ($th >= 9) {
            $king = collect($player['heroes'] ?? [])->where('name', 'Barbarian King')->first();
            $queen = collect($player['heroes'] ?? [])->where('name', 'Archer Queen')->first();

            if ($king && $queen) {
                $avgHeroLevel = ($king['level'] + $queen['level']) / 2;
                $expectedHeroLevel = ($th - 8) * 5 + 10; // Simple heuristic
                if ($avgHeroLevel < $expectedHeroLevel * 0.7) {
                    $heroesReady = false;
                }
            }
        }

        if (!$warPref) {
            return [
                'status' => 'NOT READY',
                'status_id' => 'not_ready',
                'isReady' => false,
                'reason' => 'Preferensi perang Anda sedang MATI (Opt Out).',
                'label' => 'TIDAK SIAP'
            ];
        }

        if ($heroesReady && $troopsReady) {
            return [
                'status' => 'WAR READY',
                'status_id' => 'ready',
                'isReady' => true,
                'reason' => 'Hero dan pasukan Anda sudah dalam kondisi prima untuk War.',
                'label' => 'SIAP PERANG'
            ];
        }

        if ($heroesReady || $troopsReady) {
            return [
                'status' => 'SEMI READY',
                'status_id' => 'semi_ready',
                'isReady' => false,
                'reason' => $heroesReady ? 'Hero siap, tapi pasukan utama masih perlu ditingkatkan.' : 'Pasukan siap, tapi Hero masih terlalu rendah.',
                'label' => 'SEMI SIAP'
            ];
        }

        return [
            'status' => 'NOT READY',
            'status_id' => 'not_ready',
            'isReady' => false,
            'reason' => 'Level Hero dan pasukan masih jauh dari standar operasional War.',
            'label' => 'TIDAK SIAP'
        ];
    }
}
