<?php

namespace App\Models\Overwatch;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Competitive extends Model
{
    use SoftDeletes;

    protected $table = 'overwatch_players_competitive';
    protected $fillable = [
        'player_id',
        'player_rank',
        'player_level',
        'player_prestige',
        'player_tier',
        'player_avatar',
        'total',
        'wins',
        'loses',
        'ties',
        'player_gold_medals',
        'player_silver_medals',
        'player_bronze_medals',
];

    public function player() {
        return $this->hasOne(Player::class, 'id', 'player_id');
    }

    public function trends() {
        return $this->hasMany(Trends::class, 'player_id', 'player_id')->orderBy('id', 'DESC');
    }

    public function playtime() {
        return $this->hasMany(Playtime::class, 'player_id', 'player_id')->orderBy('character_time', 'DESC');
    }
}