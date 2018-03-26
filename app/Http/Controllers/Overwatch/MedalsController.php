<?php
namespace App\Http\Controllers\Overwatch;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Carbon\Carbon;
use App\Helper\Caching;

use App\Models\Overwatch\Competitive;


class MedalsController extends Controller {

	public function index() {
		$cache = new Caching();
		$cache->setPrefix('OVERWATCH_MEDALS');
		if ($cache->hasData()) {
			return $cache->getData();
		} else {
			$array = [
				'gold'		=> 0,
				'silver'	=> 0,
				'bronze'	=> 0
			];
			$model = Competitive::orderBy('id', 'DESC')->get();
			foreach($model as $item) {
				$array['gold'] += $item->player_gold_medals;
				$array['silver'] += $item->player_silver_medals;
				$array['bronze'] += $item->player_bronze_medals;
			}
			$cache->setData($array);
			return $array;
		}
	}

}