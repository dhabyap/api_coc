<?php

namespace App\Services;

class PlayerInsightService
{
    protected WarReadinessService $warService;
    protected UpgradePriorityService $priorityService;
    protected StrategyRecommendationService $strategyService;
    protected CocMaxLevelService $maxLevelService;

    public function __construct(
        WarReadinessService $warService,
        UpgradePriorityService $priorityService,
        StrategyRecommendationService $strategyService,
        CocMaxLevelService $maxLevelService
    ) {
        $this->warService = $warService;
        $this->priorityService = $priorityService;
        $this->strategyService = $strategyService;
        $this->maxLevelService = $maxLevelService;
    }

    /**
     * Build all insights for a player by coordinating specialized services.
     */
    public function getAllInsights(array $player): array
    {
        $th = $player['townHallLevel'] ?? 1;

        $troopData = $this->analyzeTroops($player['troops'] ?? [], $th);
        $spellData = $this->analyzeSpells($player['spells'] ?? [], $th);
        $heroData = $this->analyzeHeroes($player['heroes'] ?? [], $th);
        $equipmentData = $this->analyzeEquipment($player['heroEquipment'] ?? [], $th);

        $health = $this->calculateAccountHealth($heroData, $troopData, $spellData);
        $rushStatus = $this->detectRushStatus($player, $heroData, $troopData);

        // Re-calculate hero order with TH-relative logic
        $heroOrder = $this->calculateHeroUpgradeOrder($player['heroes'] ?? [], $th);
        $clanContribution = $this->analyzeClanContribution($player);

        // Advanced Services
        $warReadiness = $this->warService->calculateStatus($player, $heroData, $troopData);
        $strategy = $this->strategyService->getRecommendations($player, [
            'heroes' => $heroData,
            'troops' => $troopData,
            'spells' => $spellData,
            'heroOrder' => $heroOrder
        ]);

        $evolution = $this->calculateEvolutionLabel(
            $health['score'],
            $rushStatus['isRushed'],
            $warReadiness['status_id'],
            $player,
            [
                'heroes' => $heroData,
                'troops' => $troopData,
                'equipment' => $equipmentData
            ]
        );

        return [
            'cv' => $this->calculateCvInsights($player, $th, $heroData, $troopData, $spellData, $equipmentData),
            'health' => $health,
            'rush' => $rushStatus,
            'evolution' => $evolution,
            'heroOrder' => $heroOrder,
            'clan' => $clanContribution,
            'warReadiness' => $warReadiness,
            'strategy' => $strategy,
            'troops' => $troopData,
            'spells' => $spellData,
            'heroes' => $heroData,
            'equipment' => $equipmentData,
            'playerStats' => [
                'level' => $player['expLevel'] ?? 0,
                'warStars' => $player['warStars'] ?? 0,
                'attackWins' => $player['attackWins'] ?? 0,
                'defenseWins' => $player['defenseWins'] ?? 0,
                'trophies' => $player['trophies'] ?? 0,
                'bestTrophies' => $player['bestTrophies'] ?? 0,
            ]
        ];
    }

    private function calculateEvolutionLabel(int $score, bool $isRushed, string $warStatus, array $player, array $insights): array
    {
        // Agile parameters
        $heroProg = $insights['heroes']['averageProgress'] ?? 0;
        $gearProg = $insights['equipment']['score'] ?? 0;
        $donationCount = $player['donations'] ?? 0;

        // Final "Strategy Score" considers balance
        $strategyScore = ($heroProg * 0.4) + ($score * 0.3) + ($gearProg * 0.2) + (min(100, $donationCount / 10) * 0.1);

        if ($strategyScore >= 90 && !$isRushed && $warStatus === 'ready') {
            return [
                'label' => 'CoC Elite',
                'description' => 'Akun Sempurna! Anda adalah standar emas pemain strategis.',
                'color' => 'yellow',
                'icon' => 'crown'
            ];
        }

        if ($strategyScore >= 75 && $warStatus !== 'not_ready') {
            return [
                'label' => 'War Veteran',
                'description' => 'Siap Tempur! Anda memiliki fondasi kuat untuk segala jenis War.',
                'color' => 'purple',
                'icon' => 'swords'
            ];
        }

        if (!$isRushed || $strategyScore >= 50) {
            return [
                'label' => 'Consistent Builder',
                'description' => 'Pembangun Disiplin! Akun Anda berkembang dengan sangat seimbang.',
                'color' => 'blue',
                'icon' => 'hammer'
            ];
        }

        return [
            'label' => 'The Fixer',
            'description' => 'Dalam Perbaikan! Anda sedang fokus membenahi kekuatan akun.',
            'color' => 'red',
            'icon' => 'wrench'
        ];
    }

    /**
     * Get the max level for a specific unit based on Town Hall.
     * This is a simplified mapping for popular units to ensure accuracy.
     */
    private function getThMaxLevel(string $type, string $name, int $th, int $apiMax): int
    {
        $customMax = $this->maxLevelService->getMaxLevel($type, $name, $th);
        return $customMax > 0 ? $customMax : $apiMax;
    }

    private function calculateAccountHealth(array $heroes, array $troops, array $spells): array
    {
        $heroScore = $heroes['averageProgress'] ?? 0;
        $troopScore = $troops['readinessScore'] ?? 0;
        $spellScore = $spells['readinessScore'] ?? 0;

        $totalScore = ($heroScore * 0.5) + ($troopScore * 0.3) + ($spellScore * 0.2);

        $status = 'Perlu Perbaikan';
        $color = 'red';

        if ($totalScore >= 85) {
            $status = 'Sangat Baik';
            $color = 'green';
        } elseif ($totalScore >= 65) {
            $status = 'Cukup Baik';
            $color = 'yellow';
        }

        return [
            'score' => round($totalScore),
            'status' => $status,
            'color' => $color,
        ];
    }

    private function detectRushStatus(array $player, array $heroes, array $troops): array
    {
        $th = $player['townHallLevel'] ?? 1;
        $reasons = [];
        $isRushed = false;

        // "Prematur" check logic
        if ($th >= 9 && $heroes['averageProgress'] < 60) {
            $isRushed = true;
            $reasons[] = "Level Hero jauh di bawah standar untuk TH{$th}.";
        }

        if ($troops['readinessScore'] < 50) {
            $isRushed = true;
            $reasons[] = "Pasukan utama masih level rendah (Prematur).";
        }

        return [
            'status' => $isRushed ? 'Prematur' : 'Solid',
            'isRushed' => $isRushed,
            'reasons' => $reasons,
        ];
    }

    private function calculateHeroUpgradeOrder(array $heroes, int $th): array
    {
        $priorityMap = [
            'Archer Queen' => 10,
            'Grand Warden' => 9,
            'Barbarian King' => 7,
            'Royal Champion' => 8,
        ];

        return collect($heroes)
            ->filter(fn($h) => ($h['village'] ?? '') === 'home')
            ->map(function ($h) use ($priorityMap, $th) {
                $maxLevel = $this->getThMaxLevel('hero', $h['name'], $th, $h['maxLevel']);
                $gap = max(0, $maxLevel - $h['level']);
                $basePriority = $priorityMap[$h['name']] ?? 1;
                $score = ($basePriority * 2) + ($gap * 0.5);
                return [
                    'name' => $h['name'],
                    'level' => $h['level'],
                    'maxLevel' => $maxLevel,
                    'score' => $score,
                    'isMax' => $h['level'] >= $maxLevel
                ];
            })
            ->filter(fn($h) => !$h['isMax'])
            ->sortByDesc('score')
            ->values()
            ->all();
    }

    private function analyzeTroops(array $troops, int $th): array
    {
        $airTroops = ['Balloon', 'Dragon', 'Baby Dragon', 'Electro Dragon', 'Dragon Rider', 'Minion', 'Lava Hound', 'Ice Hound', 'Inferno Dragon', 'Super Dragon'];
        $groundTroops = ['Barbarian', 'Archer', 'Giant', 'Goblin', 'Wall Breaker', 'Wizard', 'Healer', 'P.E.K.K.A', 'Golem', 'Valkyrie', 'Hog Rider', 'Bowler', 'Ice Golem', 'Yeti', 'Headhunter', 'Apprentice Warden', 'Root Rider', 'Miner'];

        $filtered = collect($troops)->filter(fn($t) => ($t['village'] ?? '') === 'home');
        if ($filtered->isEmpty())
            return ['readinessScore' => 0, 'list' => [], 'airScore' => 0, 'groundScore' => 0];

        $list = $filtered->map(function ($t) use ($th, $airTroops, $groundTroops) {
            $maxLevel = $t['maxLevel'];
            $isMax = $t['level'] >= $maxLevel;
            $type = in_array($t['name'], $airTroops) ? 'air' : (in_array($t['name'], $groundTroops) ? 'ground' : 'other');

            return [
                'name' => $t['name'],
                'level' => $t['level'],
                'maxLevel' => $maxLevel,
                'isMax' => $isMax,
                'type' => $type,
                'progress' => round(($t['level'] / max(1, $maxLevel)) * 100),
                'status' => $isMax ? 'MAX' : ($t['level'] >= $maxLevel * 0.8 ? 'DEKAT' : 'RENDAH')
            ];
        })->sortByDesc('level')->sortByDesc('isMax')->values();

        $airList = $list->where('type', 'air');
        $groundList = $list->where('type', 'ground');

        $readinessScore = $list->avg('progress');
        $airScore = $airList->isEmpty() ? 0 : $airList->avg('progress');
        $groundScore = $groundList->isEmpty() ? 0 : $groundList->avg('progress');

        return [
            'readinessScore' => round($readinessScore),
            'airScore' => round($airScore),
            'groundScore' => round($groundScore),
            'list' => $list->all(),
        ];
    }

    private function analyzeSpells(array $spells, int $th): array
    {
        $filtered = collect($spells)->filter(fn($s) => ($s['village'] ?? '') === 'home');
        if ($filtered->isEmpty())
            return ['readinessScore' => 0, 'list' => []];

        $list = $filtered->map(function ($s) use ($th) {
            $maxLevel = $this->getThMaxLevel('spell', $s['name'], $th, $s['maxLevel']);
            $isMax = $s['level'] >= $maxLevel;
            return [
                'name' => $s['name'],
                'level' => $s['level'],
                'maxLevel' => $maxLevel,
                'isMax' => $isMax,
                'progress' => round(($s['level'] / max(1, $maxLevel)) * 100),
            ];
        })->sortByDesc('level')->sortByDesc('isMax')->values();

        $avgProgress = $list->avg('progress');

        return [
            'readinessScore' => round($avgProgress),
            'list' => $list->all(),
        ];
    }

    private function analyzeHeroes(array $heroes, int $th): array
    {
        $homeHeroes = collect($heroes)->filter(fn($h) => ($h['village'] ?? '') === 'home');
        if ($homeHeroes->isEmpty())
            return ['averageProgress' => 0, 'list' => []];

        $list = $homeHeroes->map(function ($h) use ($th) {
            $maxLevel = $this->getThMaxLevel('hero', $h['name'], $th, $h['maxLevel']);

            // Extract active equipment
            $activeEquipment = collect($h['equipment'] ?? [])->map(function ($e) {
                return [
                    'name' => $e['name'],
                    'level' => $e['level'],
                    'maxLevel' => $e['maxLevel']
                ];
            })->all();

            return [
                'name' => $h['name'],
                'level' => $h['level'],
                'maxLevel' => $maxLevel,
                'isMax' => $h['level'] >= $maxLevel,
                'progress' => round(($h['level'] / max(1, $maxLevel)) * 100),
                'activeEquipment' => $activeEquipment
            ];
        })->sortByDesc('level')->values();

        $totalProgress = $list->sum('progress');

        return [
            'averageProgress' => round($totalProgress / $list->count()),
            'list' => $list->all()
        ];
    }

    private function analyzeEquipment(array $equipment, int $th): array
    {
        $filtered = collect($equipment);
        if ($filtered->isEmpty())
            return ['score' => 0, 'list' => []];

        $list = $filtered->map(function ($e) {
            $isEpic = $e['maxLevel'] >= 27;
            return [
                'name' => $e['name'],
                'level' => $e['level'],
                'maxLevel' => $e['maxLevel'],
                'isEpic' => $isEpic,
                'rarity' => $isEpic ? 'Epic' : 'Common',
                'isMax' => $e['level'] >= $e['maxLevel'],
                'progress' => round(($e['level'] / max(1, $e['maxLevel'])) * 100),
            ];
        })->sortByDesc('level')->sortByDesc('isEpic')->values();

        $avgProgress = $list->sum('progress') / $list->count();

        return [
            'score' => round($avgProgress),
            'list' => $list->all()
        ];
    }

    private function analyzeClanContribution(array $player): array
    {
        $roles = ['leader' => 'Pemimpin', 'coLeader' => 'Wakil Pemimpin', 'elder' => 'Sesepuh', 'member' => 'Anggota'];
        return [
            'donations' => $player['donations'] ?? 0,
            'capital' => number_format($player['clanCapitalContributions'] ?? 0),
            'role' => $roles[$player['role'] ?? 'member'] ?? 'Anggota',
        ];
    }

    private function calculateCvInsights(array $player, int $th, array $heroes, array $troops, array $spells, array $equipment): array
    {
        $siegeData = $this->analyzeSiege($player['troops'] ?? [], $th);
        $petData = $this->analyzePets($player['troops'] ?? [], $th);
        $superTroopData = $this->analyzeSuperTroops($player['troops'] ?? [], $th);

        $heroMaxed = collect($heroes['list'])->every(fn($h) => $h['isMax']);
        $troopMaxed = collect($troops['list'])->every(fn($t) => $t['isMax']);
        $spellMaxed = collect($spells['list'])->every(fn($s) => $s['isMax']);
        $gearMaxed = collect($equipment['list'])->every(fn($e) => $e['isMax']);
        $siegeMaxed = collect($siegeData['list'])->every(fn($s) => $s['isMax']);
        $petMaxed = $th >= 14 ? collect($petData['list'])->every(fn($p) => $p['isMax']) : true;

        $heroWeight = 0.30;
        $troopWeight = 0.25;
        $spellWeight = 0.10;
        $siegeWeight = 0.10;
        $petWeight = 0.10;
        $gearWeight = 0.15;

        $heroScore = $heroes['averageProgress'] ?? 0;
        $troopScore = $troops['readinessScore'] ?? 0;
        $spellScore = $spells['readinessScore'] ?? 0;
        $siegeScore = $siegeData['readinessScore'] ?? 0;
        $petScore = $petData['readinessScore'] ?? 0;
        $gearScore = $equipment['score'] ?? 0;

        $cvHealth = (
            ($heroScore * $heroWeight) +
            ($troopScore * $troopWeight) +
            ($spellScore * $spellWeight) +
            ($siegeScore * $siegeWeight) +
            ($petScore * $petWeight) +
            ($gearScore * $gearWeight)
        );

        $healthLabel = 'WAR READY';
        $healthColor = 'green';
        if ($cvHealth < 70) {
            $healthLabel = 'NOT READY';
            $healthColor = 'red';
        } elseif ($cvHealth < 85) {
            $healthLabel = 'SEMI READY';
            $healthColor = 'yellow';
        }

        return [
            'health' => [
                'score' => round($cvHealth),
                'label' => $healthLabel,
                'color' => $healthColor
            ],
            'badges' => [
                'heroMax' => $heroMaxed,
                'troopMax' => $troopMaxed,
                'spellMax' => $spellMaxed,
                'gearMax' => $gearMaxed,
                'fullyMaxed' => ($heroMaxed && $troopMaxed && $spellMaxed && $gearMaxed && $siegeMaxed && $petMaxed)
            ],
            'superTroops' => $superTroopData,
            'siege' => $siegeData,
            'pets' => $petData
        ];
    }

    private function analyzeSiege(array $troops, int $th): array
    {
        $sieges = ['Wall Wrecker', 'Battle Blimp', 'Stone Slammer', 'Siege Barracks', 'Log Launcher', 'Flame Finger', 'Battle Drill'];
        $filtered = collect($troops)->filter(fn($t) => in_array($t['name'], $sieges));

        if ($filtered->isEmpty())
            return ['readinessScore' => 0, 'list' => []];

        $list = $filtered->map(function ($t) use ($th) {
            $maxLevel = $this->getThMaxLevel('siege', $t['name'], $th, $t['maxLevel']);
            return [
                'name' => $t['name'],
                'level' => $t['level'],
                'maxLevel' => $maxLevel,
                'isMax' => $t['level'] >= $maxLevel,
                'progress' => round(($t['level'] / max(1, $maxLevel)) * 100),
            ];
        });

        return [
            'readinessScore' => round($list->avg('progress') ?? 0),
            'list' => $list->all()
        ];
    }

    private function analyzePets(array $troops, int $th): array
    {
        $pets = ['L.A.S.S.I', 'Electro Owl', 'Mighty Yak', 'Unicorn', 'Frosty', 'Diggy', 'Poison Lizard', 'Phoenix', 'Spirit Fox'];
        $filtered = collect($troops)->filter(fn($t) => in_array($t['name'], $pets));

        if ($filtered->isEmpty())
            return ['readinessScore' => 0, 'list' => []];

        $list = $filtered->map(function ($t) use ($th) {
            $maxLevel = $this->getThMaxLevel('pet', $t['name'], $th, $t['maxLevel']);
            return [
                'name' => $t['name'],
                'level' => $t['level'],
                'maxLevel' => $maxLevel,
                'isMax' => $t['level'] >= $maxLevel,
                'progress' => round(($t['level'] / max(1, $maxLevel)) * 100),
            ];
        });

        return [
            'readinessScore' => round($list->avg('progress') ?? 0),
            'list' => $list->all()
        ];
    }

    private function analyzeSuperTroops(array $troops, int $th): array
    {
        $superTroopsToRecognize = [
            'Super Barbarian',
            'Super Archer',
            'Super Giant',
            'Super Wall Breaker',
            'Sneaky Goblin',
            'Super Miner',
            'Rocket Balloon',
            'Inferno Dragon',
            'Super Valkyrie',
            'Super Witch',
            'Ice Hound',
            'Super Bowler',
            'Super Dragon',
            'Root Rider',
            'Meteor Golem'
        ];

        $normalTroopMap = collect($troops)->keyBy('name');

        return collect($superTroopsToRecognize)->map(function ($stName) use ($normalTroopMap, $th) {
            $baseName = $this->maxLevelService->getBaseTroopForSuper($stName) ?? $stName;
            $baseTroop = $normalTroopMap[$baseName] ?? null;

            if (!$baseTroop) {
                return [
                    'name' => $stName,
                    'status' => 'NOT UNLOCKED',
                    'isMax' => false
                ];
            }

            $maxLevel = $this->getThMaxLevel('troop', $baseName, $th, $baseTroop['maxLevel']);
            $isMax = $baseTroop['level'] >= $maxLevel;

            return [
                'name' => $stName,
                'status' => $isMax ? 'MAX' : 'NOT MAX',
                'isMax' => $isMax
            ];
        })->all();
    }
}
