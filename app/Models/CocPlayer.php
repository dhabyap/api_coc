<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CocPlayer extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_tag',
        'player_name',
        'town_hall_level',
        'payload',
        'last_fetched_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'last_fetched_at' => 'datetime',
    ];
}
