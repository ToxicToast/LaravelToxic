<?php
namespace App\Http\Controllers\Overwatch;

use App\Http\Controllers\Controller;
use \App\Http\Resources\Overwatch\RankedCollection;
use GuzzleHttp\Client;
use App\Helper\Caching;
use App\Models\Overwatch\Player;
use App\Models\Overwatch\Competitive;
use App\Models\Overwatch\Trends;

class RankedController extends Controller {

	public function index() {
		$cache = new Caching();
		$cache->setPrefix('OVERWATCH_RANKED');
		if ($cache->hasData()) {
			return $cache->getData();
		} else {
			$model = Competitive::orderBy('player_rank', 'DESC')
			->get();
			if (!$model->isEmpty()) {
				$collection = new RankedCollection($model);
				$cache->setData($collection);
				return $collection;
			} else {
				return $this->returnDefault(false);
			}
		}
	}

	public function update($id) {
			$model = Player::OnlyActive()->where('id', $id)->first();
			//
			$array = [
				'user'	=> $model->name,
				'tag'		=> $model->hashtag,
				'userId'=> $model->id
			];
			//
			\Artisan::call('overwatch:profiles', $array);
	}

	private function returnDefault($error = true) {
		if ($error) {
				return abort(404);
		} else {
				return [];
		}
}
}