<?php

namespace App\Models\Overwatch;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trends extends Model
{
    use SoftDeletes;

    protected $table = 'overwatch_players_competitive_trends';
    protected $fillable = ['player_id', 'player_rank', 'player_tier'];

    public function player() {
        return $this->hasOne(Player::class, 'id', 'player_id');
    }

    public function competitive() {
        return $this->hasOne(Competitive::class, 'player_id', 'player_id');
    }
}