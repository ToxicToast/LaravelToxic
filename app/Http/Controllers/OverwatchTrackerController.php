<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\Caching;
use GuzzleHttp\Client;

class OverwatchTrackerController extends Controller
{
	private $trackerData = [];

	public function getRankedData() {
		dd($this->getOverwatchApiData('ToxicToast-1192'));
	}

	public function getTrackerMedalsData() {
		return [
			'gold'		=> 0,
			'silver'	=> 0,
			'bronze'	=> 0
		];
		//
		$goldMedals = 42+41+14+29+25+50;
		$silverMedals = 46+26+21+21+28+48;
		$bronzeMedals = 52+18+4+23+36+42;
		//
		return [
			'gold'		=> $goldMedals,
			'silver'	=> $silverMedals,
			'bronze'	=> $bronzeMedals
		];
	}

	public function getTrackerTrendsData() {
		$beloor = [
			$this->seasionStart(),
			$this->afterPlacement(2382),
		];
		$dragon = [
			$this->seasionStart(),
			$this->afterPlacement(2061),
		];
		$hanter = [
			$this->seasionStart(),
			$this->afterPlacement(2222),
		];
		$noobster = [
			$this->seasionStart(),
			$this->afterPlacement(2078),
			$this->iterateGameList(1, 2101),
			$this->iterateGameList(2, 2128),
			$this->iterateGameList(3, 2057),
			$this->iterateGameList(4, 2062),
			$this->iterateGameList(5, 2081),
			$this->iterateGameList(6, 2099),
		];
		$sensimillia = [
			$this->seasionStart(),
			$this->afterPlacement(2273),
			$this->iterateGameList(1, 2212),
			$this->iterateGameList(2, 2152),
			$this->iterateGameList(3, 2200),
			$this->iterateGameList(4, 2246),
			$this->iterateGameList(5, 2292),
			$this->iterateGameList(6, 2317),
		];
		$toxictoast = [
			$this->seasionStart(),
			$this->afterPlacement(2264),
			$this->iterateGameList(1, 2262),
			$this->iterateGameList(2, 2292),
			$this->iterateGameList(3, 2224),
			$this->iterateGameList(4, 2221),
			$this->iterateGameList(5, 2244),
			$this->iterateGameList(6, 2269),
		];
		//
		$this->pushTrackerData('BeLoor', $beloor);
		$this->pushTrackerData('DragonMG', $dragon);
		$this->pushTrackerData('HanterGER', $hanter);
		$this->pushTrackerData('Noobster', $noobster);
		$this->pushTrackerData('Sensimillia', $sensimillia);
		$this->pushTrackerData('ToxicToast', $toxictoast);
		return $this->trackerData;
	}

	private function iterateGameList($index = 1, $value) {
		return [
			'value' => $value,
			'name'	=> 'Game #' . $index
		];
	}

	private function afterPlacement($value) {
		return [
			'value' => $value,
			'name'	=> 'After Placements'
		];
	}

	private function seasionStart() {
		return [
			'value' => 0,
			'name' => 'Season Start'
		];
	}

	private function pushTrackerData($name = '', $series = []) {
		$this->trackerData[] = [
			'name' 		=> $name,
			'series'	=> $series
		];
	}


	private function getOverwatchApiData($bnetAccount) {
		$url = "https://owapi.net/api/v3/u/" . $bnetAccount . "/blob?platform=pc&region=eu&format=json_pretty";
		//
		$client = new Client();
		$response = $client->get($url);
		$json = json_decode($response->getBody()->getContents(), true);
		return $json['eu'];
	}
}