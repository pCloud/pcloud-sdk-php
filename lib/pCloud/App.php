<?php

namespace pCloud;

class App {

	public static function loadAppInfoFile($appInfoPath) {
		if (!file_exists($appInfoPath)) {
			throw new Exception("Application config file not found");
			
		}
		return json_decode(file_get_contents($appInfoPath));
	}

	public static function getAuthorizeCodeUrl($config) {
		self::validParams($config, ["appKey"]);

		$params = [
			"client_id" => $config->appKey,
			"response_type" => "code"
		];


		if (isset($config->redirect_uri) && !empty($config->redirect_uri)) {
			$params["redirect_uri"] = $config->redirect_uri;
		}

		return "https://my.pcloud.com/oauth2/authorize?".http_build_query($params);
	}

	public static function getToken($appInfoPath, $credentialPath) {
		$appInfo = self::loadAppInfoFile($appInfoPath);

		self::validParams($appInfo, ["appKey", "appSecret", "code"]);

		$params = [
			"client_id" => $appInfo->appKey,
			"client_secret" => $appInfo->appSecret,
			"code" => $appInfo->code
		];

		$url = "https://api.pcloud.com/oauth2_token?".http_build_query($params);

		$curl = curl_init($url);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		$response = curl_exec($curl);

		if (strpos(curl_getinfo($curl, CURLINFO_CONTENT_TYPE), "application/json") !== false) {
			$response = json_decode($response);
		}

		if ($response->result == 0) {
			$token = ["access_token" => $response->access_token];
			if (!file_put_contents($credentialPath, json_encode($token, 128))) {
				throw new Exception("Couldn't write access_token");				
			}
		} else {
			throw new Exception($response->error);
		}
	}

	private static function validParams($params, $keys) {
		foreach ($keys as $key) {
			if (!isset($params->$key) || empty($params->$key)) {
				throw new Exception("\"{$key}\" not found");
			}
		}
	}
}