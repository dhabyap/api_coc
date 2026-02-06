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
        $troopData = $this->analyzeTroops($player['troops'] ?? []);
        $spellData = $this->analyzeSpells($player['spells'] ?? []);
        $heroData = $this->analyzeHeroes($player['heroes'] ?? []);
        $equipmentData = $this->analyzeEquipment($player['heroEquipment'] ?? []);

        $health = $this->calculateAccountHealth($heroData, $troopData, $spellData);
        $rushStatus = $this->detectRushStatus($player, $heroData, $troopData);
        $heroOrder = $this->calculateHeroUpgradeOrder($player['heroes'] ?? []);
        $clanContribution = $this->analyzeClanContribution($player);

        // Advanced Services
        $warReadiness = $this->warService->calculateStatus($player, $heroData, $troopData);
        $strategy = $this->strategyService->getRecommendations($player, $this->buildInsightContext($heroData, $troopData, $spellData, $heroOrder));

        return [
            'health' => $health,
            'rush' => $rushStatus,
            'heroOrder' => $heroOrder,
            'clan' => $clanContribution,
            'warReadiness' => $warReadiness, // From WarReadinessService
            'strategy' => $strategy,       // From StrategyRecommendationService
            'troops' => $troopData,
            'spells' => $spellData,
            'heroes' => $heroData,
            'equipment' => $equipmentData,
        ];
    }

    private function buildInsightContext($heroes, $troops, $spells, $heroOrder): array
    {
        return [
            'heroes' => $heroes,
            'troops' => $troops,
            'spells' => $spells,
            'heroOrder' => $heroOrder
        ];
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

        if ($th >= 9 && $heroes['averageProgress'] < 60) {
            $isRushed = true;
            $reasons[] = "Level Hero jauh di bawah standar untuk TH{$th}.";
        }

        if ($troops['readinessScore'] < 50) {
            $isRushed = true;
            $reasons[] = "Pasukan utama masih level rendah.";
        }

        return [
            'status' => $isRushed ? 'Prematur' : 'Solid',
            'isRushed' => $isRushed,
            'reasons' => $reasons,
        ];
    }

    private function calculateHeroUpgradeOrder(array $heroes): array
    {
        $priorityMap = [
            'Archer Queen' => 10,
            'Grand Warden' => 9,
            'Barbarian King' => 7,
            'Royal Champion' => 8,
        ];

        return collect($heroes)
            ->filter(fn($h) => ($h['village'] ?? '') === 'home')
            ->map(function ($h) use ($priorityMap) {
                $gap = $h['maxLevel'] - $h['level'];
                $basePriority = $priorityMap[$h['name']] ?? 1;
                $score = ($basePriority * 2) + ($gap * 0.5);
                return [
                    'name' => $h['name'],
                    'level' => $h['level'],
                    'maxLevel' => $h['maxLevel'],
                    'score' => $score,
                    'isMax' => $h['level'] >= $h['maxLevel']
                ];
            })
            ->filter(fn($h) => !$h['isMax'])
            ->sortByDesc('score')
            ->values()
            ->all();
    }

    private function analyzeTroops(array $troops): array
    {
        $filtered = collect($troops)->filter(fn($t) => ($t['village'] ?? '') === 'home');
        if ($filtered->isEmpty())
            return ['readinessScore' => 0, 'list' => []];

        $totalProgress = $filtered->sum(fn($t) => ($t['level'] / $t['maxLevel']) * 100);
        $readinessScore = $totalProgress / $filtered->count();

        return [
            'readinessScore' => round($readinessScore),
            'list' => $filtered->map(fn($t) => [
                'name' => $t['name'],
                'level' => $t['level'],
                'maxLevel' => $t['maxLevel'],
                'progress' => round(($t['level'] / $t['maxLevel']) * 100),
                'status' => $t['level'] >= $t['maxLevel'] ? 'MAX' : ($t['level'] >= $t['maxLevel'] * 0.8 ? 'DEKAT' : 'RENDAH')
            ])->values()->all(),
        ];
    }

    private function analyzeSpells(array $spells): array
    {
        $filtered = collect($spells)->filter(fn($s) => ($s['village'] ?? '') === 'home');
        if ($filtered->isEmpty())
            return ['readinessScore' => 0, 'list' => []];

        $totalProgress = $filtered->sum(fn($s) => ($s['level'] / $s['maxLevel']) * 100);

        return [
            'readinessScore' => round($totalProgress / $filtered->count()),
            'list' => $filtered->map(fn($s) => [
                'name' => $s['name'],
                'level' => $s['level'],
                'maxLevel' => $s['maxLevel'],
                'progress' => round(($s['level'] / $s['maxLevel']) * 100),
            ])->values()->all(),
        ];
    }

    private function analyzeHeroes(array $heroes): array
    {
        $homeHeroes = collect($heroes)->filter(fn($h) => ($h['village'] ?? '') === 'home');
        if ($homeHeroes->isEmpty())
            return ['averageProgress' => 0, 'list' => []];

        $totalProgress = $homeHeroes->sum(fn($h) => ($h['level'] / $h['maxLevel']) * 100);

        return [
            'averageProgress' => round($totalProgress / $homeHeroes->count()),
            'list' => $homeHeroes->values()->all()
        ];
    }

    private function analyzeEquipment(array $equipment): array
    {
        $filtered = collect($equipment);
        if ($filtered->isEmpty())
            return ['score' => 0, 'list' => []];

        $avgProgress = $filtered->sum(fn($e) => ($e['level'] / $e['maxLevel']) * 100) / $filtered->count();

        return [
            'score' => round($avgProgress),
            'list' => $filtered->sortByDesc('level')->values()->all()
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
