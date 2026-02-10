<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HeroEquipment;

class HeroEquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $epics = [
            'Giant Gauntlet' => 'SSS',
            'Frozen Arrow' => 'SS',
            'Fireball' => 'S',
            'Spiky Ball' => 'A',
        ];

        foreach ($epics as $name => $rank) {
            HeroEquipment::updateOrCreate(
                ['name' => $name],
                [
                    'rarity' => 'Epic',
                    'rank' => $rank,
                    'reason' => 'Prioritas tertinggi untuk stat Hero.',
                    'color' => 'indigo'
                ]
            );
        }

        $commons = ['Eternal Tome', 'Invisibility Vial', 'Hog Rider Puppet', 'Rage Vial'];

        foreach ($commons as $name) {
            HeroEquipment::updateOrCreate(
                ['name' => $name],
                [
                    'rarity' => 'Common',
                    'rank' => 'A+',
                    'reason' => 'Gear Common esensial yang wajib dimaksimalkan untuk menunjang performa Hero.',
                    'color' => 'purple'
                ]
            );
        }
    }
}
