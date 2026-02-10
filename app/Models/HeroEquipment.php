<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroEquipment extends Model
{
    use HasFactory;

    protected $table = 'hero_equipments';

    protected $fillable = [
        'name',
        'rarity',
        'rank',
        'reason',
        'image_path',
        'color',
    ];
}
