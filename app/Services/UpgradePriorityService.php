<?php

namespace App\Services;

class UpgradePriorityService
{
    /**
     * Get upgrade priorities based on TH and player data.
     */
    public function getPriorities(array $player, array $insights): array
    {
        $th = $player['townHallLevel'] ?? 1;

        $heroPriorities = [];
        foreach (collect($insights['heroOrder'])->take(2) as $h) {
            $heroPriorities[] = [
                'name' => $h['name'],
                'priority' => 'Tinggi',
                'reason' => "Hero ini adalah tulang punggung strategi di TH{$th}. Fokus ke level " . ($h['level'] + 1) . ".",
                'color' => 'red'
            ];
        }

        return [
            'heroes' => $heroPriorities,
            'troops' => $this->getTroopPriorities($th, $insights['troops']['list']),
            'spells' => $this->getSpellPriorities($th, $insights['spells']['list'])
        ];
    }

    private function getTroopPriorities(int $th, array $troopList): array
    {
        $recs = [];
        $troops = collect($troopList);

        // TH 9-10 Focus
        if ($th <= 10) {
            $basic = ['Giant', 'Wizard', 'Balloon'];
            foreach ($basic as $name) {
                $t = $troops->where('name', $name)->first();
                if ($t && $t['level'] < $t['maxLevel']) {
                    $recs[] = [
                        'type' => 'PASUKAN',
                        'name' => $name,
                        'priority' => 'Sedang',
                        'reason' => "Di TH{$th}, {$name} sangat penting untuk farming dan basic war attack.",
                        'color' => 'amber'
                    ];
                }
            }
        }

        // TH 11-12 Focus (Queen Walk related)
        if ($th >= 11 && $th <= 12) {
            $key = ['Healer', 'Electro Dragon', 'Miner'];
            foreach ($key as $name) {
                $t = $troops->where('name', $name)->first();
                if ($t && $t['level'] < $t['maxLevel']) {
                    $recs[] = [
                        'type' => 'PASUKAN',
                        'name' => $name,
                        'priority' => 'Tinggi',
                        'reason' => "Mendukung strategi Queen Walk atau E-Drag Smash yang populer di TH{$th}.",
                        'color' => 'red'
                    ];
                }
            }
        }

        // TH 13-16 Focus (Hybrid/Meta)
        if ($th >= 13) {
            $meta = ['Root Rider', 'Yeti', 'Dragon Rider'];
            foreach ($meta as $name) {
                $t = $troops->where('name', $name)->first();
                if ($t && $t['level'] < $t['maxLevel']) {
                    $recs[] = [
                        'type' => 'PASUKAN',
                        'name' => $name,
                        'priority' => 'Tinggi',
                        'reason' => "Pasukan Meta terkuat untuk strategi War di tingkatan Town Hall tinggi.",
                        'color' => 'red'
                    ];
                }
            }
        }

        return $recs;
    }

    private function getSpellPriorities(int $th, array $spellList): array
    {
        $recs = [];
        $spells = collect($spellList);

        $essential = ['Rage Spell', 'Freeze Spell', 'Invisibility Spell'];
        foreach ($essential as $name) {
            $s = $spells->where('name', $name)->first();
            if ($s && $s['level'] < $s['maxLevel']) {
                $recs[] = [
                    'type' => 'MANTRA',
                    'name' => $name,
                    'priority' => 'Tinggi',
                    'reason' => "{$name} adalah spell kunci yang menentukan keberhasilan serangan bintang 3.",
                    'color' => 'red'
                ];
            }
        }

        return $recs;
    }
}
