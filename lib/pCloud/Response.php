<?php

namespace pCloud;

class Response {

	private $response;
	private $httpCode;
	private $contentType;

	function __construct($response, $httpCode, $contentType) {
		$this->response = $response;
		$this->httpCode = $httpCode;
		$this->contentType = $contentType;

		$this->parseJson();
	}

	private function parseJson() {
		if (strpos($this->contentType, "application/json") !== false) {
			$this->response = json_decode($this->response);
		}
	}
	
	public function get() {
		if ($this->httpCode != 200) {
			throw new Exception("HTTP Code = {$this->httpCode}");
		}

		if ($this->response->result == 0) {
			return $this->parseResponse();
		} else {
			throw new Exception($this->response->error);
		}	
	}

	private function parseResponse() {
		$return = new \stdClass();
		foreach ($this->response as $key => $value) {
			if ($key == "result") continue;
			$return->$key = $value;
		}
		return $return;
	}
}