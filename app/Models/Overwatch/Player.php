<?php

namespace App\Models\Overwatch;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Player extends Model
{
    use SoftDeletes;

    protected $table = 'overwatch_players';
    protected $fillable = ['name', 'slug', 'hashtag', 'active'];

    public function scopeOnlyActive($query) {
        return $query->where('active', '1');
    }

    public function competitive() {
        return $this->hasOne(Competitive::class, 'player_id', 'id');
    }

    public function trends() {
        return $this->hasMany(Trends::class, 'player_id', 'id')->orderBy('id', 'ASC');
    }
}
