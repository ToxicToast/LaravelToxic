<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

use \App\Http\Requests\CommentRequest;

use \App\Models\BlogPosts;
use \App\Models\BlogCategories;
use \App\Models\BlogComments;

use App\Helper\Caching;

use \App\Http\Resources\PostCollection;
use \App\Http\Resources\PostResource;
use \App\Http\Resources\CategoryCollection;
use \App\Http\Resources\CategoryResource;
use \App\Http\Resources\CommentsCollection;
use \App\Http\Resources\CommentsResource;

class BlogController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['only' => ['addComments']]);
    }

    public function getPosts() {
        $cache = new Caching();
        $cache->setPrefix('BLOG_GETPOSTS');
        if ($cache->hasData()) {
            return $cache->getData();
        } else {
            $model = BlogPosts::OnlyActive()
            ->orderBy('id', 'DESC')
            // ->paginate(10);
            ->get();
            if (!$model->isEmpty()) {
                $collection = new PostCollection($model);
                $cache->setData($collection);
                return $collection;
            } else {
                return $this->returnDefault(true);
            }
        }
    }

    public function getPost($id) {
        $cache = new Caching();
        $cache->setPrefix('BLOG_GETPOST_NO_' . $id);
        if ($cache->hasData()) {
            return $cache->getData();
        } else {
            $model = BlogPosts::OnlyActive()
            ->orderBy('id', 'DESC')
            ->where('id', $id)
            ->first();
            if (!empty($model)) {
                $collection = new PostResource($model);
                $cache->setData($collection);
                return $collection;
            } else {
                return $this->returnDefault(true);
            }
        }
    }

    public function getLastPost() {
        $cache = new Caching();
        $cache->setPrefix('BLOG_GETLASTPOST');
        if ($cache->hasData()) {
            return $cache->getData();
        } else {
            $model = BlogPosts::OnlyActive()
                    ->orderBy('id', 'DESC')
                    ->take(1)
                    ->first();
            if (!empty($model)) {
                $collection = new PostResource($model);
                $cache->setData($collection);
                return $collection;
            } else {
                return $this->returnDefault(false);
            }
        }
    }

    public function getCategoryPosts($category_id) {
        if ($category_id > 0) {
            $cache = new Caching();
            $cache->setPrefix('BLOG_GETCATEGORYPOSTS_NO_' . $category_id);
            if ($cache->hasData()) {
                return $cache->getData();
            } else {
                $model = BlogPosts::OnlyActive()
                ->orderBy('id', 'DESC')
                ->where('category_id', $category_id)
                ->get();
                if (!$model->isEmpty()) {
                    $collection = new PostCollection($model);
                    $cache->setData($collection);
                    return $collection;
                } else {
                return $this->returnDefault(false);
                }
            }
        } else {
            return $this->getPosts();
        }
    }

    public function getCategories() {
        $cache = new Caching();
        $cache->setPrefix('BLOG_GETCATEGORIES');
        if ($cache->hasData()) {
            return $cache->getData();
        } else {
            $model = BlogCategories::orderBy('id', 'ASC')
            ->get();
            if (!$model->isEmpty()) {
                $collection = new CategoryCollection($model);
                $cache->setData($collection);
                return $collection;
            } else {
                return $this->returnDefault(false);
            }
        }
    }

    public function getComments($post_id) {
        $cache = new Caching();
        $cache->setPrefix('BLOG_GETCOMMENTS_NO_' . $post_id);
        if ($cache->hasData()) {
            return $cache->getData();
        } else {
            $model = BlogComments::OnlyActive()
            ->orderBy('id', 'DESC')
            ->where('post_id', $post_id)
            ->get();
            if (!$model->isEmpty()) {
                $collection = new CommentsCollection($model);
                $cache->setData($collection);
                return $collection;
            } else {
                return $this->returnDefault(false);
            }
        }
    }

    public function addComments(Request $request) {
        try {
            $commentArray = $request->all();
            $modelArray = [
                'post_id'   => $commentArray['postId'],
                'user_id'   => $commentArray['userId'],
                'text'      => $commentArray['comment'],
                'active'    => '1',
            ];
            $model = new BlogComments($modelArray);
            $model->save();
            //
            $this->removeCache([
                'BLOG_GETCOMMENTS_NO_' . $commentArray['postId'],
                'BLOG_GETPOST_NO_' . $commentArray['postId'],
                'BLOG_GETPOSTS'
            ]);
            //
            return response($model, 200);
        } catch (\Exception $e) {
            return response($e, 400);
        }
    }

    private function returnDefault($error = true) {
        if ($error) {
            return abort(404);
        } else {
            return [
                'data' => [],
                'count' => 0
            ];
        }
    }

    private function removeCache($array) {
        foreach($array as $item) {
            $cache = new Caching();
            $cache->setPrefix($item);
            $cache->removeData();
        }
    }
}
