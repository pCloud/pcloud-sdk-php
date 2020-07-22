<?php

namespace pCloud;

class Request {

	private $app;
	private $host;

	function __construct(App $app) {
		$this->app = $app;
		$this->host = Config::getApiHostByLocationId($app->getLocationId());
	}

	private function getGlobalParams() {
		$globalParams = array();

		$auth = array(
			"access_token" => $this->app->getAccessToken()
		);

		return array_merge($auth, $globalParams);
	}

	private function buildUrl($method, $params = null) {
		$url = $this->host.$method;

		if (!is_null($params)) {
			$url .= "?".http_build_query($params);
		}

		return $url;
	}

	private function buildCurl($url) {
		$curl = new Config::$curllib($url);

		$curl->set(CURLOPT_USERAGENT, "pCloud PHP SDK");
		$curl->set(CURLOPT_SSL_VERIFYPEER, false);
		$curl->set(CURLOPT_RETURNTRANSFER, true);

		return $curl;
	}

	public function get($method, $params = array()) {
		$globalParams = $this->getGlobalParams();

		$url = $this->buildUrl($method, array_merge($globalParams, $params));

		$curl = $this->buildCurl($url);

		return $curl->exec();
	}

	public function post($method, $params = array()) {
		$globalParams = $this->getGlobalParams();

		$url = $this->buildUrl($method, $globalParams);

		$curl = $this->buildCurl($url);

		$curl->set(CURLOPT_CUSTOMREQUEST, "POST");
		$curl->set(CURLOPT_POSTFIELDS, $params);

		return $curl->exec();
	}

	public function put($method, $params = array(), $content) {
		$globalParams = $this->getGlobalParams();

		$url = $this->buildUrl($method, array_merge($globalParams, $params));

		$curl = $this->buildCurl($url);

		$curl->set(CURLOPT_CUSTOMREQUEST, "PUT");
		$curl->set(CURLOPT_POSTFIELDS, $content);
		$curl->set(CURLOPT_HTTPHEADER, array("Content-Type: text/html"));
		$curl->set(CURLOPT_BINARYTRANSFER, true);

		return $curl->exec();
	}
}