<?php

namespace App\Services;

class PlayerInsightService
{
    protected WarReadinessService $warService;
    protected UpgradePriorityService $priorityService;
    protected StrategyRecommendationService $strategyService;

    public function __construct(
        WarReadinessService $warService,
        UpgradePriorityService $priorityService,
        StrategyRecommendationService $strategyService
    ) {
        $this->warService = $warService;
        $this->priorityService = $priorityService;
        $this->strategyService = $strategyService;
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

        return [
            'health' => $health,
            'rush' => $rushStatus,
            'heroOrder' => $heroOrder,
            'clan' => $clanContribution,
            'warReadiness' => $warReadiness,
            'strategy' => $strategy,
            'troops' => $troopData,
            'spells' => $spellData,
            'heroes' => $heroData,
            'equipment' => $equipmentData,
        ];
    }

    /**
     * Get the max level for a specific unit based on Town Hall.
     * This is a simplified mapping for popular units to ensure accuracy.
     */
    private function getThMaxLevel(string $type, string $name, int $th, int $apiMax): int
    {
        // Many times the API already provides the TH max. 
        // We only override if we detect it's returning the global max (e.g. 95 for TH10).

        if ($type === 'hero') {
            $heroMaxes = [
                'Barbarian King' => [7 => 10, 8 => 20, 9 => 30, 10 => 40, 11 => 50, 12 => 65, 13 => 75, 14 => 80, 15 => 90, 16 => 95],
                'Archer Queen' => [9 => 30, 10 => 40, 11 => 50, 12 => 65, 13 => 75, 14 => 80, 15 => 90, 16 => 95],
                'Grand Warden' => [11 => 20, 12 => 40, 13 => 50, 14 => 55, 15 => 65, 16 => 70],
                'Royal Champion' => [13 => 25, 14 => 30, 15 => 40, 16 => 45]
            ];

            if (isset($heroMaxes[$name][$th])) {
                return $heroMaxes[$name][$th];
            }
        }

        // If the API max is much higher than what's possible for this TH, 
        // it's likely a global max. We default back to the provided API max if unsure,
        // but for heroes we use our verified table.
        return $apiMax;
    }

    private function calculateAccountHealth(array $heroes, array $troops, array $spells): array
    {
        $heroScore = $heroes['averageProgress'] ?? 0;
        $troopScore = $troops['readinessScore'] ?? 0;
        $spellScore = $spells['readinessScore'] ?? 0;

        $totalScore = ($heroScore * 0.5) + ($troopScore * 0.3) + ($spellScore * 0.2);

        $status = 'Perlu Peningkatan';
        if ($totalScore >= 85)
            $status = 'Sangat Baik';
        elseif ($totalScore >= 65)
            $status = 'Cukup Baik';

        return [
            'score' => round($totalScore),
            'status' => $status,
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
        $filtered = collect($troops)->filter(fn($t) => ($t['village'] ?? '') === 'home');
        if ($filtered->isEmpty())
            return ['readinessScore' => 0, 'list' => []];

        $list = $filtered->map(function ($t) use ($th) {
            $maxLevel = $t['maxLevel'];
            $isMax = $t['level'] >= $maxLevel;
            return [
                'name' => $t['name'],
                'level' => $t['level'],
                'maxLevel' => $maxLevel,
                'isMax' => $isMax,
                'progress' => round(($t['level'] / max(1, $maxLevel)) * 100),
                'status' => $isMax ? 'MAX' : ($t['level'] >= $maxLevel * 0.8 ? 'DEKAT' : 'RENDAH')
            ];
        })->sortByDesc('isMax')->values();

        $totalProgress = $list->sum('progress');
        $readinessScore = $totalProgress / $list->count();

        return [
            'readinessScore' => round($readinessScore),
            'list' => $list->all(),
        ];
    }

    private function analyzeSpells(array $spells, int $th): array
    {
        $filtered = collect($spells)->filter(fn($s) => ($s['village'] ?? '') === 'home');
        if ($filtered->isEmpty())
            return ['readinessScore' => 0, 'list' => []];

        $list = $filtered->map(function ($s) use ($th) {
            $maxLevel = $s['maxLevel'];
            return [
                'name' => $s['name'],
                'level' => $s['level'],
                'maxLevel' => $maxLevel,
                'isMax' => $s['level'] >= $maxLevel,
                'progress' => round(($s['level'] / max(1, $maxLevel)) * 100),
            ];
        })->sortByDesc('isMax')->values();

        return [
            'readinessScore' => round($list->sum('progress') / $list->count()),
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
            return [
                'name' => $h['name'],
                'level' => $h['level'],
                'maxLevel' => $maxLevel,
                'progress' => round(($h['level'] / max(1, $maxLevel)) * 100),
            ];
        })->values();

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
            return [
                'name' => $e['name'],
                'level' => $e['level'],
                'maxLevel' => $e['maxLevel'],
                'isMax' => $e['level'] >= $e['maxLevel'],
                'progress' => round(($e['level'] / max(1, $e['maxLevel'])) * 100),
            ];
        })->sortByDesc('isMax')->values();

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

    public function getRecommendations(array $player, array $insights): array
    {
        return $this->priorityService->getPriorities($player, $insights);
    }
}
