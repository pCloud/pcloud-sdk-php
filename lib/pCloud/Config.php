<?php

namespace pCloud;

class Config {
	static public $usHost = "https://api.pcloud.com/";
	static public $euHost = "https://eapi.pcloud.com/";
	static public $curllib = "pCloud\Curl";
	static public $filePartSize = 10485760;

	static public function getApiHostByLocationId($locationid) {
		if ($locationid == 2) {
			return self::$euHost;
		} else {
			return self::$usHost;
		}
	}
}