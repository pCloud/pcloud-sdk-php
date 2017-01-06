<?php

namespace pCloud;

use InvalidArgumentException;

class Curl {

	protected $curl;
	private $headers = array();

	function __construct($url = false) {
		if (!$url) {
			throw new InvalidArgumentException("Invalid URL");	
		}

		$this->curl = curl_init($url);
	}

	function __destruct() {
		curl_close($this->curl);
	}

	public function set($option, $value) {
		curl_setopt($this->curl, $option, $value);
	}

	public function addHeader($header) {
		$this->headers[] = $header;
	}

	public function exec() {
		$this->set(CURLOPT_HEADER, $this->headers);

		$r = curl_exec($this->curl);

		$httpCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
		$contentType = curl_getinfo($this->curl, CURLINFO_CONTENT_TYPE);

		$info = curl_getinfo($this->curl);

		$response = new Response($r, $httpCode, $contentType);

		return $response->get();
	}
}