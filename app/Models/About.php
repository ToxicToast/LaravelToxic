<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class About extends Model
{
    use SoftDeletes;

    protected $table = 'about';
    protected $fillable = ['title', 'slug', 'text', 'active'];

    public function scopeOnlyActive($query) {
        return $query->where('active', '1');
    }
}
