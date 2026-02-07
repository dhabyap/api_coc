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
