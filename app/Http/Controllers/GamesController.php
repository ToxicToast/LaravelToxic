<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\Caching;

use \App\Models\Games;

use \App\Http\Resources\GamesCollection;

class GamesController extends Controller
{
    public function __construct() {
        $this->middleware('cors', ['only' => 'searchGame']);
    }

    public function getGames() {
        $cache = new Caching();
        $cache->setPrefix('GAMES_GETGAMES');
        if ($cache->hasData()) {
            return $cache->getData();
        } else {
            $model = Games::OnlyActive()
            ->orderBy('id', 'DESC')
            ->get();
            if (!$model->isEmpty()) {
                $collection = new GamesCollection($model);
                $cache->setData($collection);
                return $collection;
            } else {
                return $this->returnDefault(true);
            }
            return $this->returnDefault(false);
        }
    }

    public function searchGame(Request $request) {
        $data = $request->all();
        $searchValue = $data['data'];
        //
        $model = Games::OnlyActive()
        ->orderBy('id', 'DESC')
        ->where('name', 'like', '%' . $searchValue. '%')
        ->get();
        if (!$model->isEmpty()) {
            $collection = new GamesCollection($model);
            return $collection;
        } else {
            return $this->returnDefault(true);
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
