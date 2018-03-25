<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogComments extends Model
{
    use SoftDeletes;
    protected $table = 'blog_comments';

    protected $fillable = ['post_id', 'user_id', 'text', 'active'];

    public function scopeOnlyActive($query) {
        return $query->where('active', '1');
    }

    public function user() {
        return $this->hasOne(Users::class, 'id', 'user_id');
    }
}
