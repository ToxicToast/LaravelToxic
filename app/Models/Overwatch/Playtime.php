<?php

namespace App\Models\Overwatch;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Playtime extends Model
{
    use SoftDeletes;

    protected $table = 'overwatch_players_competitive_playtime';
    protected $fillable = ['player_id', 'character_name', 'character_time'];

    public function player() {
        return $this->hasOne(Player::class, 'id', 'player_id');
    }
}