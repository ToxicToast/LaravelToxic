<?php
namespace App\Http\Controllers\Overwatch;

use App\Http\Controllers\Controller;
use \App\Http\Resources\Overwatch\RankedCollection;
use GuzzleHttp\Client;
use App\Helper\Caching;
use App\Models\Overwatch\Player;
use App\Models\Overwatch\Competitive;
use App\Models\Overwatch\Trends;
use Carbon\Carbon;

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

	public function profile($id) {
		$cache = new Caching();
		$cache->setPrefix('OVERWATCH_RANKED_PROFILE_' . $id);
		if ($cache->hasData()) {
			return $cache->getData();
		} else {
			$model = Competitive::orderBy('player_rank', 'DESC')->where('player_id', $id)
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
			$cache = new Caching();
			$cache->setPrefix('OVERWATCH_RANKED_PROFILE_' . $id);
			$cache->removeData();
			$cache->setPrefix('OVERWATCH_RANKED');
			$cache->removeData();
			//
			$model = Player::OnlyActive()->where('id', $id)->first();
			if ($this->canUpdateUser($model->updated_at) >= 12) {
				$model->updated_at = Carbon::now();
				$model->save();
				$array = [
					'user'	=> $model->name,
					'tag'		=> $model->hashtag,
					'userId'=> $model->id
				];
				\Artisan::call('overwatch:profiles', $array);
			} else {
				return $this->returnDefault(true);
			}
	}

	private function returnDefault($error = true) {
			if ($error) {
					return abort(404);
			} else {
					return [];
			}
	}

	private function canUpdateUser($date) {
		$now = Carbon::now();
		$lastUpdated = Carbon::parse($date);
		//
		return $now->diffInHours($lastUpdated);
	}

}