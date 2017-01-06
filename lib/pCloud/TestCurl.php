<?php

namespace pCloud;

use InvalidArgumentException;

class TestCurl extends Curl {

	function __construct($url) {
		parent::__construct($url);
	}

	public function exec() {
		$url = curl_getinfo($this->curl, CURLINFO_EFFECTIVE_URL);
		return $this->parseUrl($url);
	}

	private function parseUrl($url) {
		$parts = explode("?", $url);

		if (count($parts) == 1) {
			return $parts[0];
		} else {
			parse_str($parts[1], $query);
			unset($query["access_token"]);
			$parts[1] = http_build_query($query);
			return strlen($parts[1]) == 0 ? $parts[0] : implode("?", $parts);
		}
	}
}