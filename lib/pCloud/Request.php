<?php

namespace pCloud;

class Request {

	private $credentialPath;
	private $host;

	function __construct($skip_getapiserver=NULL) {
		$this->credentialPath = Config::$credentialPath;
		if ($skip_getapiserver) {
			$this->host = Config::$host;
		} else {
			try {
				$this->host = $this->getApiServer();
			} catch(Exception $e) {
				// Request shouldn't fail if there is an issue with a single API
				$this->host = Config::$host;
			}
		}
	}

	private function getApiServer() {
		$url = Config::$host . 'getapiserver';
		$curl = $this->buildCurl($url);
		$getapiserver_resp = $curl->exec();
		return "https://" . $getapiserver_resp->api[0] . '/';
	}

	private function getGlobalParams() {
		$globalParams = array();

		$auth = Auth::getAuth($this->credentialPath);

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
