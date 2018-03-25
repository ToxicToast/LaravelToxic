<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use \App\Models\BlogCategories;
use \App\Models\BlogComments;
use \App\Models\Users;

class BlogPosts extends Model
{
    use SoftDeletes;

    protected $table = 'blog_posts';
    protected $fillable = ['category_id', 'user_id', 'thumbnail_id', 'title', 'slug', 'intro', 'fulltext', 'active'];

    public function scopeOnlyActive($query) {
        return $query->where('active', '1');
    }

    public function category() {
        return $this->hasOne(BlogCategories::class, 'id', 'category_id');
    }

    public function comments() {
        return $this->hasMany(BlogComments::class, 'post_id', 'id')->where('active', '1')->orderBy('id', 'DESC');
    }

    public function user() {
        return $this->hasOne(Users::class, 'id', 'user_id');
    }
}
