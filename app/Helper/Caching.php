<?php

namespace App\Helper;

use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class Caching {

	private $duration;
	private $prefix;

	public function __construct() {
		$this->duration = config('cache.duration');
		$this->prefix = config('cache.prefix');
	}

	public function setDuration($duration) {
		$this->duration = $duration;
	}

	public function setPrefix($prefix) {
		$this->prefix = $prefix;
	}

	public function setData($data) {
		if (!Cache::has($this->prefix)) {
			Cache::put($this->prefix, $data, $this->duration);
		}
	}

	public function getData() {
		return Cache::get($this->prefix, null);
	}

	public function hasData() {
		return Cache::has($this->prefix);
	}

	public function removeData() {
		Cache::forget($this->prefix);
	}
}