<?php
namespace App\Http\Controllers\Overwatch;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use App\Helper\Caching;

class StreamersController extends Controller {

	public function index() {
		$cache = new Caching();
		$cache->setPrefix('OVERWATCH_STREAMERS');
		if ($cache->hasData()) {
			return $cache->getData();
		} else {
			$array = [
				[
					'channel'	=> 'ToxicToast',
					'stream'	=> $this->isStreamOnline('toxictoast')
				]
			];
			$cache->setData($array);
			return $array;
		}
	}

	private function isStreamOnline($channel = 'toxictoast') {
		$clientId = "pu0zath2073g3qplw3d5kfntogi6qvb";
		$url = "https://api.twitch.tv/kraken/streams/" . $channel . "?client_id=" . $clientId;
		//
		$client = new Client();
		$response = $client->get($url);
		$json = json_decode($response->getBody()->getContents(), true);
		//
		$returnArray = [
			'online' 	=> false,
			'viewer'	=> 0,
			'game'		=> 'No Game Available'
		];
		if ($json['stream']) {
			$returnArray['online'] = true;
			$returnArray['viewer'] = $json['stream']['viewers'];
			$returnArray['game'] = $json['stream']['game'];
		}
		return $returnArray;
		//return $json['stream'] ? ['online' => true, 'stream' => $json['stream']] ? ['online' => false, 'stream' => $json['stream']];
	}

}