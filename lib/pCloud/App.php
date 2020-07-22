<?php

namespace pCloud;

class App {
	private $appKey;
	private $appSecret;
	private $redirect_uri;
	private $access_token;
	private $locationid;

	public function setAppKey($appKey) {
		$this->appKey = $appKey;
	}

	public function getAppKey() {
		return $this->appKey;
	}

	public function setAppSecret($appSecret) {
		$this->appSecret = $appSecret;
	}

	public function getAppSecret() {
		return $this->appSecret;
	}

	public function setRedirectURI($redirect_uri) {
		$this->redirect_uri = $redirect_uri;
	}

	public function getRedirectURI() {
		return $this->redirect_uri;
	}

	public function setAccessToken($access_token) {
		$this->access_token = $access_token;
	}

	public function getAccessToken() {
		return $this->access_token;
	}

	public function setLocationId($locationid) {
		$this->locationid = $locationid;
	}

	public function getLocationId() {
		return $this->locationid;
	}

	public function getAuthorizeCodeUrl() {
		self::validParams(["appKey"]);

		$params = [
			"client_id" => $this->appKey,
			"response_type" => "code"
		];


		if (isset($this->redirect_uri) && !empty($this->redirect_uri)) {
			$params["redirect_uri"] = $this->redirect_uri;
		}

		return "https://my.pcloud.com/oauth2/authorize?".http_build_query($params);
	}

	public function getTokenFromCode($code, $locationid) {
		self::validParams(["appKey", "appSecret"]);

		$params = [
			"client_id" => $this->appKey,
			"client_secret" => $this->appSecret,
			"code" => $code
		];

		$host = Config::getApiHostByLocationId($locationid);

		$url = $host . "/oauth2_token?" . http_build_query($params);

		$curl = curl_init($url);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		$response = curl_exec($curl);

		if (strpos(curl_getinfo($curl, CURLINFO_CONTENT_TYPE), "application/json") !== false) {
			$response = json_decode($response);
		}

		if ($response->result == 0) {
			$token = ["access_token" => $response->access_token, "locationid" => $response->locationid];
			return $token;
		} else {
			throw new Exception($response->error);
		}
	}

	private function validParams($keys) {
		foreach ($keys as $key) {
			if (!isset($this->$key) || empty($this->$key)) {
				throw new Exception("\"{$key}\" not found");
			}
		}
	}
}