<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\Caching;

use \App\Models\About;

use \App\Http\Resources\AboutCollection;

class AboutController extends Controller
{
    public function getFaq() {
        $cache = new Caching();
        $cache->setPrefix('ABOUT_GETFAQ');
        if ($cache->hasData()) {
            return $cache->getData();
        } else {
            $model = About::OnlyActive()
            ->orderBy('id', 'DESC')
            ->get();
            if (!$model->isEmpty()) {
                $collection = new AboutCollection($model);
                $cache->setData($collection);
                return $collection;
            } else {
                return $this->returnDefault(true);
            }
            return $this->returnDefault(false);
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
}
