<?php

namespace App\Services;

class PlayerInsightService
{
    /**
     * Build all insights for a player in Bahasa Indonesia.
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

        $status = 'Perlu Peningkatan';
        if ($totalScore >= 85)
            $status = 'Sangat Baik';
        elseif ($totalScore >= 65)
            $status = 'Cukup Baik';

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
            'Minion Prince' => 5,
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
        // List all home village troops
        $filtered = collect($troops)
            ->filter(fn($t) => ($t['village'] ?? '') === 'home');

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
                'status' => $t['level'] >= $t['maxLevel'] ? 'MAX' : ($t['level'] >= $t['maxLevel'] * 0.8 ? 'DEKAT' : 'RENDAH')
            ])->values()->all(),
        ];
    }

    private function analyzeSpells(array $spells): array
    {
        $filtered = collect($spells)
            ->filter(fn($s) => ($s['village'] ?? '') === 'home');

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
        // List all equipment
        $filtered = collect($equipment);
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

        $roles = [
            'leader' => 'Pemimpin',
            'coLeader' => 'Wakil Pemimpin',
            'elder' => 'Sesepuh',
            'member' => 'Anggota',
        ];

        return [
            'donations' => $donations,
            'received' => $received,
            'ratio' => round($ratio, 2),
            'capital' => number_format($capital),
            'role' => $roles[$player['role'] ?? 'member'] ?? 'Anggota',
            'activity' => $donations + $received > 1000 ? 'Tinggi' : ($donations + $received > 100 ? 'Sedang' : 'Rendah'),
        ];
    }

    private function calculateWarReadiness(array $player, array $heroes, array $troops): array
    {
        $warPref = ($player['warPreference'] ?? 'out') === 'in';
        $troopReady = $troops['readinessScore'] > 75;

        $isReady = $warPref && $troopReady;

        return [
            'isReady' => $isReady,
            'status' => $isReady ? 'Siap Perang' : 'Belum Siap',
            'reason' => !$warPref ? 'Preferensi Perang diatur ke TIDAK.' : (!$troopReady ? 'Level pasukan masih kurang.' : 'Semua sistem siap.'),
        ];
    }

    public function getRecommendations(array $player, array $insights): array
    {
        $recs = [];

        foreach (array_slice($insights['heroOrder'], 0, 2) as $h) {
            $recs[] = [
                'title' => "Upgrade " . $h['name'],
                'reason' => "Prioritas hero tertinggi untuk TH{$player['townHallLevel']}. Saat ini Lv. {$h['level']}",
                'priority' => 'Tinggi'
            ];
        }

        $lowTroops = collect($insights['troops']['list'])->where('status', 'RENDAH')->take(1);
        foreach ($lowTroops as $t) {
            $recs[] = [
                'title' => "Upgrade " . $t['name'],
                'reason' => "Level pasukan ini sangat rendah (Lv. {$t['level']}).",
                'priority' => 'Sedang'
            ];
        }

        if ($insights['spells']['readinessScore'] < 70) {
            $recs[] = [
                'title' => "Fokus pada Spell Utama",
                'reason' => "Rage dan Freeze sangat penting untuk serangan tingkat tinggi.",
                'priority' => 'Tinggi'
            ];
        }

        return array_slice($recs, 0, 5);
    }
}
