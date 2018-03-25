<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use \App\Models\BlogPosts;

class BlogCategories extends Model
{
    use SoftDeletes;

    protected $table = 'blog_categories';
    protected $fillable = ['title', 'slug'];

    public function posts() {
        return $this->belongsTo(BlogPosts::class);
    }
}
