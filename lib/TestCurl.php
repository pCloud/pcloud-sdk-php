<?php

namespace pCloud\Sdk;

use stdClass;

/**
 * This class is used for testing perposes
 *
 * @noinspection PhpUnused
 */
class TestCurl extends Curl
{

	/**
	 * @param string $url
	 * @noinspection PhpUnused
	 */
	function __construct(string $url)
	{
		parent::__construct($url);
	}

	/**
	 * @return stdClass
	 * @noinspection PhpUnused
	 */
	public function exec(): stdClass
	{
		$url = curl_getinfo($this->curl, CURLINFO_EFFECTIVE_URL);
		$url = $this->parseUrl($url);

		$result = new stdClass();
		$result->url = $url;

		return $result;
	}

	/**
	 * Parse URL
	 *
	 * @param string $url
	 *
	 * @return string
	 */
	private function parseUrl(string $url): string
	{

		$parts = explode("?", $url);

		if (count($parts) == 1) {
			return strval($parts[0]);
		} else {
			parse_str($parts[1], $query);
			unset($query["access_token"]);
			$parts[1] = http_build_query($query);
			return strlen($parts[1]) == 0 ? $parts[0] : implode("?", $parts);
		}
	}
}