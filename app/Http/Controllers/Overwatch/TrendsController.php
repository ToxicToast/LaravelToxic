<?php
namespace App\Http\Controllers\Overwatch;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Carbon\Carbon;
use App\Helper\Caching;

use \App\Http\Resources\Overwatch\TrendsCollection;

use App\Models\Overwatch\Player;
use App\Models\Overwatch\Trends;

class TrendsController extends Controller {

	public function index() {
		$cache = new Caching();
		$cache->setPrefix('OVERWATCH_TRENDS');
		if ($cache->hasData()) {
			return $cache->getData();
		} else {
			$array = [];
			$model = Player::OnlyActive()->orderby('id', 'ASC')->get();
			if (!$model->isEmpty()) {
				$i = 0;
				foreach($model as $player) {
					$trends = new TrendsCollection($player->trends);
					$array[$i]['name'] = $player->name;
					$array[$i]['series'][] = [
						'name'	=> 'Season Start',
						'value'	=> 0
					];
					foreach($trends as $trend) {
						$array[$i]['series'][] = [
							'name'	=> $this->makeDate($trend->created_at),
							'value'	=> $trend->player_rank
						];
					}
					$i++;
				}
			}
			$cache->setData($array);
			return $array;
		}
	}

	private function makeDate($date) {
		$newDate = Carbon::parse($date);
		return $newDate->format('d.m.Y');
	}

}