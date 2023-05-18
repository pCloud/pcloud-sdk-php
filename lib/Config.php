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

    /** @var string $usHost USA main API endpoint.  */
	static public string $usHost = "https://api.pcloud.com/";

    /** @var string $euHost European main API endpoint. */
	static public string $euHost = "https://eapi.pcloud.com/";

    /** @var string $curllib Path to cURL library. */
	static public string $curllib = "pCloud\Sdk\Curl";

    /** @var int $filePartSize File chunk size. */
	static public int $filePartSize = 10485760;

	/**
	 * @param int|string $locationId Location ID, 1 - USA, 2 - EU.
	 * @return string
	 */
	static public function getApiHostByLocationId(int|string $locationId): string
	{
		if (intval($locationId) == 2) {
			return self::$euHost;
		} else {
			return self::$usHost;
		}
	}
}