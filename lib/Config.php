<?php

namespace pCloud\Sdk;

/**
 * Configuration class
 * Here you will find some basic API related configuration parameters.
 * They are provided and setup by pCloud, and shouldn't be modified.
 *
 * @package pCloud\Sdk
 */
class Config {
	
	static public $usHost = "https://api.pcloud.com/";
	static public $euHost = "https://eapi.pcloud.com/";
	static public $curllib = "pCloud\Sdk\Curl";
	static public $filePartSize = 10485760;

	/**
	 * @param int $locationid
	 * @return string
	 */
	static public function getApiHostByLocationId(int $locationid): string
	{
		if ($locationid == 2) {
			return self::$euHost;
		} else {
			return self::$usHost;
		}
	}
}