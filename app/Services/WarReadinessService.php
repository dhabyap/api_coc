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
                'status' => 'Opt Out',
                'status_id' => 'not_ready',
                'isReady' => false,
                'reason' => 'Anda memilih untuk TIDAK ikut War (Opt Out) di dalam game.',
                'label' => 'OPT OUT (PASIF)'
            ];
        }

        if ($heroesReady && $troopsReady) {
            return [
                'status' => 'War Ready',
                'status_id' => 'ready',
                'isReady' => true,
                'reason' => 'Hero dan Pasukan Utama Anda sudah sesuai standar Operasional War untuk TH' . $th . '.',
                'label' => 'SIAP TEMPUR'
            ];
        }

        if ($heroesReady || $troopsReady) {
            $reason = $heroesReady
                ? 'Hero Anda sudah kuat, namun level Pasukan Utama masih di bawah standar War TH' . $th . '.'
                : 'Pasukan Anda sudah kuat, namun level Hero masih terlalu rendah untuk War TH' . $th . '.';

            return [
                'status' => 'Semi Ready',
                'status_id' => 'semi_ready',
                'isReady' => false,
                'reason' => $reason,
                'label' => 'SEMI-SIAP (RISIKO)'
            ];
        }

        return [
            'status' => 'Not Ready',
            'status_id' => 'not_ready',
            'isReady' => false,
            'reason' => 'Level Hero dan Pasukan Utama masih jauh di bawah standar untuk melakukan serangan War yang efektif.',
            'label' => 'BELUM SIAP'
        ];
    }
}
