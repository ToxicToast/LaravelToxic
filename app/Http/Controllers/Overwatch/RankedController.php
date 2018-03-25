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
		$model = Competitive::orderBy('player_rank', 'DESC')
			->get();
			if (!$model->isEmpty()) {
				$collection = new RankedCollection($model);
				return $collection;
			} else {
				return $this->returnDefault(true);
			}
	}
}