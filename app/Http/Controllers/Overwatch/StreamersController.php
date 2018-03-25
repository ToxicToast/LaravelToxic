<?php
namespace App\Http\Controllers\Overwatch;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;

class StreamersController extends Controller {

	public function index() {
		return [
			[
				'channel'	=> 'ToxicToast',
				'stream'	=> $this->isStreamOnline('toxictoast')
			],
		];
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