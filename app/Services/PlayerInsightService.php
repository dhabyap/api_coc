<?php

namespace App\Services;

class PlayerInsightService
{
    /**
     * Build all insights for a player.
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
        $warReadiness = $this->calculateWarReadiness($player, $heroData, $troopData);

        return [
            'health' => $health,
            'rush' => $rushStatus,
            'heroOrder' => $heroOrder,
            'clan' => $clanContribution,
            'warReadiness' => $warReadiness,
            'troops' => $troopData,
            'spells' => $spellData,
            'heroes' => $heroData,
            'equipment' => $equipmentData,
        ];
    }

    private function calculateAccountHealth(array $heroes, array $troops, array $spells): array
    {
        $heroScore = $heroes['averageProgress'] ?? 0;
        $troopScore = $troops['readinessScore'] ?? 0;
        $spellScore = $spells['readinessScore'] ?? 0;

        $totalScore = ($heroScore * 0.5) + ($troopScore * 0.3) + ($spellScore * 0.2);

        $status = 'Needs Improvement';
        if ($totalScore >= 85)
            $status = 'Excellent';
        elseif ($totalScore >= 65)
            $status = 'Good';

        return [
            'score' => round($totalScore),
            'status' => $status,
            'heroWeight' => round($heroScore),
            'troopWeight' => round($troopScore),
            'spellWeight' => round($spellScore),
        ];
    }

    private function detectRushStatus(array $player, array $heroes, array $troops): array
    {
        $th = $player['townHallLevel'] ?? 1;
        $reasons = [];
        $isRushed = false;

        if ($th >= 9 && $heroes['averageProgress'] < 60) {
            $isRushed = true;
            $reasons[] = "Hero levels are significantly behind for TH{$th}.";
        }

        if ($troops['readinessScore'] < 50) {
            $isRushed = true;
            $reasons[] = "Core offensive troops are underleveled.";
        }

        return [
            'status' => $isRushed ? 'Rushed' : 'Solid',
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
            'Minion Prince' => 5,
        ];

        return collect($heroes)
            ->filter(fn($h) => ($h['village'] ?? '') === 'home')
            ->map(function ($h) use ($priorityMap) {
                $gap = $h['maxLevel'] - $h['level'];
                $basePriority = $priorityMap[$h['name']] ?? 1;
                // Higher score = higher priority
                $score = ($basePriority * 2) + ($gap * 0.5);
                return [
                    'name' => $h['name'],
                    'level' => $h['level'],
                    'maxLevel' => $h['maxLevel'],
                    'gap' => $gap,
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
        $metaTroops = [
            'Barbarian',
            'Archer',
            'Giant',
            'Balloon',
            'Wizard',
            'Dragon',
            'P.E.K.K.A',
            'Miner',
            'Hog Rider',
            'Electro Dragon',
            'Dragon Rider',
            'Root Rider',
            'Yeti'
        ];

        $filtered = collect($troops)
            ->filter(fn($t) => in_array($t['name'], $metaTroops) && ($t['village'] ?? '') === 'home');

        if ($filtered->isEmpty())
            return ['readinessScore' => 0, 'list' => []];

        $maxedCount = $filtered->filter(fn($t) => $t['level'] >= $t['maxLevel'])->count();
        $totalProgress = $filtered->sum(fn($t) => ($t['level'] / $t['maxLevel']) * 100);
        $readinessScore = $totalProgress / $filtered->count();

        return [
            'readinessScore' => round($readinessScore),
            'maxedCount' => $maxedCount,
            'totalCount' => $filtered->count(),
            'list' => $filtered->map(fn($t) => [
                'name' => $t['name'],
                'level' => $t['level'],
                'maxLevel' => $t['maxLevel'],
                'progress' => round(($t['level'] / $t['maxLevel']) * 100),
                'status' => $t['level'] >= $t['maxLevel'] ? 'MAX' : ($t['level'] >= $t['maxLevel'] * 0.8 ? 'NEAR' : 'LOW')
            ])->values()->all(),
        ];
    }

    private function analyzeSpells(array $spells): array
    {
        $metaSpells = ['Rage Spell', 'Freeze Spell', 'Healing Spell', 'Invisibility Spell', 'Recall Spell', 'Overgrowth Spell', 'Lightning Spell'];

        $filtered = collect($spells)
            ->filter(fn($s) => in_array($s['name'], $metaSpells) && ($s['village'] ?? '') === 'home');

        if ($filtered->isEmpty())
            return ['readinessScore' => 0, 'list' => []];

        $totalProgress = $filtered->sum(fn($s) => ($s['level'] / $s['maxLevel']) * 100);
        $readinessScore = $totalProgress / $filtered->count();

        return [
            'readinessScore' => round($readinessScore),
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
            return ['averageProgress' => 0];

        $totalProgress = $homeHeroes->sum(fn($h) => ($h['level'] / $h['maxLevel']) * 100);

        return [
            'averageProgress' => $totalProgress / $homeHeroes->count(),
            'list' => $homeHeroes->values()->all()
        ];
    }

    private function analyzeEquipment(array $equipment): array
    {
        $filtered = collect($equipment)->filter(fn($e) => ($e['maxLevel'] ?? 0) >= 18);
        if ($filtered->isEmpty())
            return ['score' => 0, 'list' => []];

        $maxed = $filtered->filter(fn($e) => $e['level'] >= $e['maxLevel'])->count();
        $nearMax = $filtered->filter(fn($e) => $e['level'] >= $e['maxLevel'] * 0.8 && $e['level'] < $e['maxLevel'])->count();

        $avgProgress = $filtered->sum(fn($e) => ($e['level'] / $e['maxLevel']) * 100) / $filtered->count();

        return [
            'score' => round($avgProgress),
            'maxedCount' => $maxed,
            'nearMaxCount' => $nearMax,
            'list' => $filtered->sortByDesc('level')->values()->all()
        ];
    }

    private function analyzeClanContribution(array $player): array
    {
        $donations = $player['donations'] ?? 0;
        $received = $player['donationsReceived'] ?? 0;
        $capital = $player['clanCapitalContributions'] ?? 0;

        $ratio = $received > 0 ? $donations / $received : $donations;

        return [
            'donations' => $donations,
            'received' => $received,
            'ratio' => round($ratio, 2),
            'capital' => number_format($capital),
            'role' => ucwords($player['role'] ?? 'Member'),
            'activity' => $donations + $received > 1000 ? 'High' : ($donations + $received > 100 ? 'Medium' : 'Low'),
        ];
    }

    private function calculateWarReadiness(array $player, array $heroes, array $troops): array
    {
        $isHeroUpgrading = false; // Note: CoC API doesn't explicitly show "upgrading" status in basic player endpoint usually, 
        // unless we compare current level/max and maybe other flags if they exist. 
        // But usually, if hero is not available it's because of upgrade.

        $warPref = ($player['warPreference'] ?? 'out') === 'in';
        $troopReady = $troops['readinessScore'] > 75;

        $isReady = $warPref && $troopReady;

        return [
            'isReady' => $isReady,
            'status' => $isReady ? 'Ready for War' : 'Not Ready',
            'reason' => !$warPref ? 'War Preference is set to OUT.' : (!$troopReady ? 'Core troops are underleveled.' : 'All systems go.'),
        ];
    }

    public function getRecommendations(array $player, array $insights): array
    {
        $recs = [];

        // Hero Priority
        foreach (array_slice($insights['heroOrder'], 0, 2) as $h) {
            $recs[] = [
                'title' => "Upgrade " . $h['name'],
                'reason' => "Highest priority hero for TH{$player['townHallLevel']}. Current Lv. {$h['level']}",
                'priority' => 'High'
            ];
        }

        // Troop Priority
        $lowTroops = collect($insights['troops']['list'])->where('status', 'LOW')->take(1);
        foreach ($lowTroops as $t) {
            $recs[] = [
                'title' => "Upgrade " . $t['name'],
                'reason' => "Meta troop is significantly underleveled (Lv. {$t['level']}).",
                'priority' => 'Medium'
            ];
        }

        // Spell Priority
        if ($insights['spells']['readinessScore'] < 70) {
            $recs[] = [
                'title' => "Focus on Core Spells",
                'reason' => "Rage and Freeze are essential for high-level war attacks.",
                'priority' => 'High'
            ];
        }

        return array_slice($recs, 0, 5);
    }
}
