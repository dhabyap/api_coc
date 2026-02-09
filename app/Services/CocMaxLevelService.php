<?php

namespace App\Services;

class CocMaxLevelService
{
    /**
     * Get the max level for a specific unit based on Town Hall.
     * Data updated up to TH18.
     */
    public function getMaxLevel(string $type, string $name, int $th): int
    {
        $th = max(1, min(18, $th));

        return match ($type) {
            'hero' => $this->getHeroMax($name, $th),
            'troop' => $this->getTroopMax($name, $th),
            'spell' => $this->getSpellMax($name, $th),
            'siege' => $this->getSiegeMax($name, $th),
            'pet' => $this->getPetMax($name, $th),
            'equipment' => $this->getEquipmentMax($name, $th),
            default => 0,
        };
    }

    private function getHeroMax(string $name, int $th): int
    {
        $heroes = [
            'Barbarian King' => [7 => 10, 8 => 20, 9 => 30, 10 => 40, 11 => 50, 12 => 65, 13 => 75, 14 => 80, 15 => 90, 16 => 95, 17 => 100, 18 => 105],
            'Archer Queen' => [9 => 30, 10 => 40, 11 => 50, 12 => 65, 13 => 75, 14 => 80, 15 => 90, 16 => 95, 17 => 100, 18 => 105],
            'Grand Warden' => [11 => 20, 12 => 40, 13 => 50, 14 => 55, 15 => 65, 16 => 70, 17 => 75, 18 => 80],
            'Royal Champion' => [13 => 25, 14 => 30, 15 => 40, 16 => 45, 17 => 50, 18 => 55],
            'Minion Prince' => [17 => 90, 18 => 95],
        ];

        return $this->resolveFromMap($heroes, $name, $th);
    }

    private function getTroopMax(string $name, int $th): int
    {
        $troops = [
            'Barbarian' => [1 => 1, 3 => 2, 5 => 3, 6 => 4, 7 => 5, 8 => 6, 9 => 7, 10 => 7, 11 => 8, 12 => 9, 13 => 9, 14 => 10, 15 => 11, 16 => 11, 17 => 12, 18 => 12],
            'Archer' => [1 => 1, 3 => 2, 5 => 3, 6 => 4, 7 => 5, 8 => 6, 9 => 7, 10 => 7, 11 => 8, 12 => 9, 13 => 9, 14 => 10, 15 => 11, 16 => 11, 17 => 13, 18 => 13],
            'Giant' => [1 => 1, 3 => 2, 5 => 3, 6 => 4, 7 => 5, 8 => 6, 9 => 6, 10 => 7, 11 => 8, 12 => 9, 13 => 9, 14 => 10, 15 => 11, 16 => 12, 17 => 13, 18 => 14],
            'Wall Breaker' => [2 => 1, 3 => 2, 5 => 3, 6 => 4, 7 => 5, 8 => 5, 9 => 6, 10 => 7, 11 => 8, 12 => 9, 13 => 9, 14 => 10, 15 => 11, 16 => 12, 17 => 13, 18 => 14],
            'Balloon' => [2 => 1, 3 => 2, 5 => 3, 6 => 4, 7 => 5, 8 => 5, 9 => 6, 10 => 7, 11 => 8, 12 => 9, 13 => 9, 14 => 10, 15 => 10, 16 => 11, 17 => 12, 18 => 12],
            'Wizard' => [2 => 1, 3 => 2, 5 => 3, 6 => 4, 7 => 5, 8 => 6, 9 => 6, 10 => 7, 11 => 8, 12 => 9, 13 => 9, 14 => 10, 15 => 11, 16 => 12, 17 => 13, 18 => 14],
            'Healer' => [6 => 1, 7 => 2, 8 => 3, 9 => 4, 10 => 5, 11 => 5, 12 => 5, 13 => 6, 14 => 7, 15 => 8, 16 => 9, 17 => 10, 18 => 11],
            'Dragon' => [7 => 1, 8 => 3, 9 => 4, 10 => 5, 11 => 6, 12 => 7, 13 => 8, 14 => 9, 15 => 10, 16 => 11, 17 => 12, 18 => 12],
            'P.E.K.K.A' => [8 => 1, 9 => 3, 10 => 4, 11 => 6, 12 => 8, 13 => 9, 14 => 10, 15 => 11, 16 => 12, 17 => 12, 18 => 13],
            'Baby Dragon' => [9 => 1, 10 => 4, 11 => 5, 12 => 6, 13 => 7, 14 => 8, 15 => 9, 16 => 10, 17 => 11, 18 => 11],
            'Miner' => [10 => 1, 11 => 3, 12 => 6, 13 => 7, 14 => 8, 15 => 9, 16 => 11, 17 => 11, 18 => 12],
            'Electro Dragon' => [11 => 1, 12 => 3, 13 => 4, 14 => 5, 15 => 6, 16 => 7, 17 => 8, 18 => 9],
            'Yeti' => [12 => 1, 13 => 3, 14 => 4, 15 => 5, 16 => 6, 17 => 7, 18 => 7],
            'Dragon Rider' => [13 => 1, 14 => 3, 15 => 3, 16 => 4, 17 => 5, 18 => 6],
            'Electro Titan' => [14 => 1, 15 => 3, 16 => 3, 17 => 4, 18 => 4],
            'Root Rider' => [15 => 1, 16 => 3, 17 => 3, 18 => 3],
            'Meteor Golem' => [17 => 1, 18 => 3],
            'Thrower' => [17 => 1, 18 => 4],
            'Ice Golem' => [11 => 1, 12 => 5, 13 => 6, 14 => 7, 15 => 8, 16 => 9, 17 => 10, 18 => 10],
            'Headhunter' => [12 => 1, 13 => 3, 14 => 3, 15 => 3, 16 => 3, 17 => 3, 18 => 4],
            'Hog Rider' => [4 => 1, 5 => 2, 6 => 3, 7 => 4, 8 => 5, 9 => 6, 10 => 7, 11 => 8, 12 => 9, 13 => 10, 14 => 11, 15 => 12, 17 => 13, 18 => 14],
            'Witch' => [9 => 1, 10 => 3, 11 => 4, 12 => 5, 17 => 6],
            'Lava Hound' => [9 => 1, 10 => 3, 11 => 4, 12 => 5, 13 => 6, 15 => 7, 16 => 8],
            'Minion' => [7 => 1, 8 => 5, 9 => 6, 10 => 7, 11 => 8, 12 => 9, 13 => 10, 15 => 11, 17 => 12],
            'Valkyrie' => [8 => 1, 9 => 4, 10 => 5, 11 => 6, 12 => 7, 13 => 8, 14 => 9, 15 => 10],
            'Bowler' => [10 => 1, 11 => 3, 12 => 4, 13 => 5, 14 => 6, 17 => 7],
            'Goblin' => [1 => 1, 3 => 2, 5 => 3, 6 => 4, 7 => 5, 8 => 6, 9 => 7, 11 => 8, 14 => 9],
        ];

        return $this->resolveFromMap($troops, $name, $th);
    }

    private function getSpellMax(string $name, int $th): int
    {
        $spells = [
            'Lightning Spell' => [5 => 4, 6 => 5, 7 => 6, 9 => 7, 11 => 8, 12 => 9, 14 => 10, 16 => 11],
            'Healing Spell' => [6 => 4, 7 => 5, 9 => 6, 11 => 7, 12 => 8, 13 => 10, 16 => 11],
            'Rage Spell' => [7 => 4, 9 => 5, 11 => 6, 12 => 6, 16 => 7, 17 => 6, 18 => 6], // Adjusting based on data: Level 6 for TH18 found in search was 6 or 7
            'Jump Spell' => [9 => 2, 10 => 3, 14 => 4, 18 => 5],
            'Freeze Spell' => [10 => 5, 11 => 6, 12 => 7],
            'Invisibility Spell' => [11 => 3, 14 => 4, 18 => 4],
            'Poison Spell' => [9 => 1, 10 => 4, 11 => 5, 13 => 7, 15 => 9, 17 => 11, 18 => 12],
            'Earthquake Spell' => [9 => 1, 10 => 4, 11 => 5, 15 => 5, 18 => 5],
            'Haste Spell' => [9 => 1, 10 => 4, 11 => 5, 13 => 5, 17 => 6, 18 => 7],
            'Bat Spell' => [10 => 1, 11 => 5, 13 => 6, 18 => 8],
            'Recall Spell' => [13 => 1, 14 => 2, 15 => 3, 16 => 4, 17 => 5, 18 => 5],
        ];

        return $this->resolveFromMap($spells, $name, $th);
    }

    private function getSiegeMax(string $name, int $th): int
    {
        $sieges = [
            'Wall Wrecker' => [12 => 3, 13 => 4, 15 => 5],
            'Battle Blimp' => [12 => 3, 13 => 4, 15 => 5],
            'Stone Slammer' => [12 => 3, 13 => 4, 15 => 5],
            'Siege Barracks' => [13 => 4, 15 => 5],
            'Log Launcher' => [13 => 4, 15 => 5],
            'Flame Finger' => [14 => 4, 15 => 5],
            'Battle Drill' => [15 => 4, 16 => 5],
        ];

        return $this->resolveFromMap($sieges, $name, $th);
    }

    private function getPetMax(string $name, int $th): int
    {
        $pets = [
            'L.A.S.S.I' => [14 => 10, 15 => 15, 16 => 15, 17 => 15, 18 => 15],
            'Electro Owl' => [14 => 10, 15 => 15, 16 => 15, 17 => 15, 18 => 15],
            'Mighty Yak' => [14 => 10, 15 => 15, 16 => 15, 17 => 15, 18 => 15],
            'Unicorn' => [14 => 10, 15 => 15, 16 => 15, 17 => 15, 18 => 15],
            'Frosty' => [15 => 10, 16 => 15, 17 => 15, 18 => 15],
            'Diggy' => [15 => 10, 16 => 10, 17 => 10, 18 => 10],
            'Poison Lizard' => [15 => 10, 16 => 15, 17 => 15, 18 => 15],
            'Phoenix' => [15 => 10, 16 => 10, 17 => 10, 18 => 10],
            'Spirit Fox' => [16 => 10, 17 => 10, 18 => 10],
            'Angry Jelly' => [16 => 10, 17 => 10, 18 => 10],
            'Sneezy' => [17 => 10, 18 => 10],
        ];

        return $this->resolveFromMap($pets, $name, $th);
    }

    private function getEquipmentMax(string $name, int $th): int
    {
        // Common equipment max depends on Blacksmith level, which depends on TH.
        // TH16 -> common max 18, epic max 27
        // TH17 -> common max 18, epic max 27 (usually)
        // TH18 -> common max 18, epic max 27

        // Actually, equipment max level is more about rarity than TH directly once you have the blacksmith, 
        // but blacksmith level is capped by TH.
        $thCaps = [
            8 => ['common' => 9, 'epic' => 12],
            9 => ['common' => 12, 'epic' => 15],
            10 => ['common' => 12, 'epic' => 15],
            11 => ['common' => 15, 'epic' => 18],
            12 => ['common' => 15, 'epic' => 18],
            13 => ['common' => 15, 'epic' => 18],
            14 => ['common' => 15, 'epic' => 18],
            15 => ['common' => 18, 'epic' => 21],
            16 => ['common' => 18, 'epic' => 27],
            17 => ['common' => 18, 'epic' => 27],
            18 => ['common' => 18, 'epic' => 27],
        ];

        $isEpic = in_array($name, [
            'Giant Gauntlet',
            'Frozen Arrow',
            'Fireball',
            'Rocket Spear',
            'Spiky Ball',
            'Magic Mirror',
            'Dark Orb',
            'Electric Boots'
        ]);

        $cap = $thCaps[$th] ?? ['common' => 1, 'epic' => 1];
        return $isEpic ? $cap['epic'] : $cap['common'];
    }

    private function resolveFromMap(array $map, string $name, int $th): int
    {
        if (!isset($map[$name]))
            return 0;

        $levels = $map[$name];
        $maxAvailable = 0;

        // Find the highest TH level that is <= $th
        ksort($levels);
        foreach ($levels as $levelTh => $lvl) {
            if ($levelTh <= $th) {
                $maxAvailable = $lvl;
            } else {
                break;
            }
        }

        return $maxAvailable;
    }

    /**
     * Map Super Troops to their base troops.
     */
    public function getBaseTroopForSuper(string $superTroopName): ?string
    {
        $map = [
            'Super Minion' => 'Minion',
            'Super Hog Rider' => 'Hog Rider',
            'Super Wizard' => 'Wizard',
            'Super Valkyrie' => 'Valkyrie',
            'Super Witch' => 'Witch',
            'Ice Hound' => 'Lava Hound',
            'Super Yeti' => 'Yeti',
            'Super Bowler' => 'Bowler',
            'Super Dragon' => 'Dragon',
            'Super Miner' => 'Miner',
            'Rocket Balloon' => 'Balloon',
            'Inferno Dragon' => 'Baby Dragon',
            'Super Wall Breaker' => 'Wall Breaker',
            'Sneaky Goblin' => 'Goblin',
            'Super Barbarian' => 'Barbarian',
            'Super Archer' => 'Archer',
            'Super Giant' => 'Giant',
        ];

        return $map[$superTroopName] ?? null;
    }
}
