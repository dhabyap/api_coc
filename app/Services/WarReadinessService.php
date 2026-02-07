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
        $groundReady = ($troopData['groundScore'] ?? 0) >= 80;
        $airReady = ($troopData['airScore'] ?? 0) >= 80;

        $isReady = $heroesReady && ($groundReady || $airReady);

        if (!$warPref) {
            return [
                'status' => 'Opt Out',
                'status_id' => 'not_ready',
                'isReady' => false,
                'color' => 'red',
                'reason' => 'Anda memilih untuk TIDAK ikut War (Opt Out) di dalam game.',
                'label' => 'OPT OUT (PASIF)'
            ];
        }

        if ($isReady) {
            $type = ($groundReady && $airReady) ? 'Darat & Udara' : ($groundReady ? 'Darat' : 'Udara');
            return [
                'status' => 'War Ready',
                'status_id' => 'ready',
                'isReady' => true,
                'color' => 'green',
                'reason' => "Hero dan Pasukan {$type} Anda sudah sesuai standar Operasional War untuk TH{$th}.",
                'label' => 'SIAP TEMPUR'
            ];
        }

        if ($heroesReady) {
            return [
                'status' => 'Semi Ready',
                'status_id' => 'semi_ready',
                'isReady' => false,
                'color' => 'yellow',
                'reason' => "Hero Anda sudah kuat, namun level Pasukan (Darat/Udara) masih di bawah standar War TH{$th}.",
                'label' => 'SEMI-SIAP (RISIKO)'
            ];
        }

        return [
            'status' => 'Not Ready',
            'status_id' => 'not_ready',
            'isReady' => false,
            'color' => 'red',
            'reason' => "Level Hero dan Pasukan masih jauh di bawah standar untuk melakukan serangan War yang efektif di TH{$th}.",
            'label' => 'BELUM SIAP'
        ];
    }
}
