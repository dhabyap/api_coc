<?php

namespace App\Services;

class StrategyRecommendationService
{
    /**
     * Get strategy-based recommendations.
     */
    public function getRecommendations(array $player, array $insights): array
    {
        $th = $player['townHallLevel'] ?? 1;

        return [
            'strategy' => $this->getSuggestedStrategy($th, $insights),
            'gear' => $this->getGearRecommendations($player['heroes'] ?? []),
            'gearPriorities' => $this->getGearUpgradePriorities($player['equipment'] ?? []),
            'superTroops' => $this->getSuperTroopSuggestions($th, $insights),
        ];
    }

    private function getSuggestedStrategy(int $th, array $insights): array
    {
        if ($th <= 10) {
            return [
                'name' => 'Witch Slap / GoBoHo',
                'description' => 'Gunakan Golem sebagai tank, diikuti Witch atau Hog Rider untuk membersihkan base.',
                'spells' => 'Heal, Rage, Poison'
            ];
        }

        if ($th <= 12) {
            return [
                'name' => 'Queen Walk Hybrid',
                'description' => 'Gunakan Archer Queen + Healer untuk membuat jalur, lalu hancurkan core dengan Miner & Hog.',
                'spells' => 'Heal, Rage, Invisibility'
            ];
        }

        return [
            'name' => 'Root Rider Smash',
            'description' => 'Strategi Meta terkuat saat ini. Gunakan Root Rider untuk menghancurkan tembok dengan mudah.',
            'spells' => 'Rage, Freeze, Overgrowth'
        ];
    }

    private function getGearRecommendations(array $heroes): array
    {
        $recs = [];
        foreach ($heroes as $h) {
            if ($h['name'] === 'Barbarian King') {
                $recs[] = ['hero' => 'King', 'best' => 'Giant Gauntlet + Rage Vial', 'reason' => 'Kombinasi terbaik untuk daya tahan dan damage area.'];
            }
            if ($h['name'] === 'Archer Queen') {
                $recs[] = ['hero' => 'Queen', 'best' => 'Invisibility Vial + Frozen Arrow', 'reason' => 'Memberikan kontrol penuh dan efek slow pada target pertahanan.'];
            }
            if ($h['name'] === 'Grand Warden') {
                $recs[] = ['hero' => 'Warden', 'best' => 'Eternal Tome + Healing Tome', 'reason' => 'Melindungi pasukan dari damage besar dan memberikan healing konstan.'];
            }
        }
        return $recs;
    }

    private function getGearUpgradePriorities(array $equipment): array
    {
        $priorities = [];
        $epicGears = collect($equipment)->where('maxLevel', '>=', 27);
        $commonGears = collect($equipment)->where('maxLevel', '<', 27);

        // Define meta rank for epic gear
        $metaEpic = [
            'Giant Gauntlet' => 'SSS (Wajib)',
            'Frozen Arrow' => 'SS (Sangat Kuat)',
            'Fireball' => 'S (Meta Baru)',
            'Spiky Ball' => 'A (Bagus)',
        ];

        foreach ($epicGears as $eg) {
            if ($eg['level'] < $eg['maxLevel']) {
                $priorities[] = [
                    'name' => $eg['name'],
                    'rank' => $metaEpic[$eg['name']] ?? 'S',
                    'reason' => 'Prioritas tertinggi karena stat pertumbuhan Epic jauh lebih besar dari Common.',
                    'color' => 'indigo'
                ];
            }
        }

        // Top common gears
        $topCommon = ['Eternal Tome', 'Invisibility Vial', 'Hog Rider Puppet', 'Rage Vial'];
        foreach ($commonGears as $cg) {
            if (in_array($cg['name'], $topCommon) && $cg['level'] < $cg['maxLevel']) {
                $priorities[] = [
                    'name' => $cg['name'],
                    'rank' => 'A+',
                    'reason' => 'Gear Common esensial yang wajib dimaksimalkan untuk menunjang performa Hero.',
                    'color' => 'purple'
                ];
            }
        }

        return collect($priorities)->take(3)->all();
    }

    private function getSuperTroopSuggestions(int $th, array $insights): array
    {
        if ($th < 11)
            return [];

        $suggestions = [
            ['name' => 'Super Wall Breaker', 'reason' => 'Wajib dibawa untuk memastikan pasukan masuk ke dalam base.'],
            ['name' => 'Sneaky Goblin', 'reason' => 'Sangat efektif untuk funneling atau menghancurkan Balai Kota (TH).']
        ];

        if ($th >= 13) {
            $suggestions[] = ['name' => 'Super Wizard', 'reason' => 'Sangat kuat jika digabungkan dengan Blizzard (Invis + Rage).'];
        }

        return $suggestions;
    }
}
