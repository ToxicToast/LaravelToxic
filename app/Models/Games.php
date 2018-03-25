<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Games extends Model
{
    use SoftDeletes;

    protected $table = 'games';
    protected $fillable = ['title', 'slug', 'active', 'platform', 'avatar_id', 'hasApi'];

    public function scopeOnlyActive($query) {
        return $query->where('active', '1');
    }
}
