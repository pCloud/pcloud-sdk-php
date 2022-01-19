<?php

namespace pCloud\Sdk;

/**
 * Main App class
 *
 * @package pCloud\Sdk
 */
class App
{
	/**
	 * App key. In https://docs.pcloud.com/my_apps/ you will find it also as "Client ID"
	 *
	 * @var string $appKey
	 */
	private $appKey = '';

	/**
	 * App secret. In https://docs.pcloud.com/my_apps/ you will find it also as "Client secret"
	 *
	 * @var string $appSecret
	 */
	private $appSecret = '';

	/**
	 * Redirect URL. It's usually set in https://docs.pcloud.com/my_apps/
	 *
	 * @var string $redirect_uri
	 */
	private $redirect_uri = '';

	/**
	 * The access token, you will receive one if you call getTokenFromCode()
	 *
	 * @var string $access_token
	 */
	private $access_token = '';

	/**
	 * Location ID, 1 - USA, 2 - EU
	 *
	 * @var int $locationid
	 */
	private $locationid = 1;

	/**
	 * cURL function execution timeout ( in seconds )
	 *
	 * @var int $curl_exec_timeout
	 */
	private $curl_exec_timeout = 3600;

	/**
	 * Set App key
	 *
	 * @param string $appKey
	 *
	 * @return void
	 * @noinspection PhpUnused
	 */
	public function setAppKey(string $appKey)
	{
		$this->appKey = trim($appKey);
	}

	/**
	 * Get App key
	 *
	 * @return string
	 * @noinspection PhpUnused
	 */
	public function getAppKey(): string
	{
		return $this->appKey;
	}

	/**
	 * Sets app secret
	 *
	 * @param string $appSecret
	 *
	 * @return void
	 * @noinspection PhpUnused
	 */
	public function setAppSecret(string $appSecret): void
	{
		$this->appSecret = trim($appSecret);
	}

	/**
	 * Get app secret
	 *
	 * @return string
	 * @noinspection PhpUnused
	 */
	public function getAppSecret(): string
	{
		return $this->appSecret;
	}

	/**
	 * Set Redirect URL
	 *
	 * @param string $redirect_uri
	 *
	 * @return void
	 * @noinspection PhpUnused
	 */
	public function setRedirectURI(string $redirect_uri): void
	{
		$this->redirect_uri = trim($redirect_uri);
	}

	/**
	 * Get Redirect URL
	 *
	 * @return string
	 * @noinspection PhpUnused
	 */
	public function getRedirectURI(): string
	{
		return $this->redirect_uri;
	}

	/**
	 * Sets the Access Token
	 *
	 * @param string $access_token
	 *
	 * @return void
	 * @noinspection PhpUnused
	 */
	public function setAccessToken(string $access_token): void
	{
		$this->access_token = trim($access_token);
	}

	/**
	 * Returns the Access Token
	 *
	 * @return string
	 * @noinspection PhpUnused
	 */
	public function getAccessToken(): string
	{
		return $this->access_token;
	}

	/**
	 * Set location ID
	 *
	 * @param string|int $locationid
	 *
	 * @return void
	 * @noinspection PhpUnused
	 */
	public function setLocationId($locationid): void
	{
		$this->locationid = intval($locationid);
	}

	/**
	 * Get location ID
	 *
	 * @return int
	 * @noinspection PhpUnused
	 */
	public function getLocationId(): int
	{
		return $this->locationid;
	}

	/**
	 * Will build and return oAuth2 authorization URL
	 * from which you will receive auth code.
	 *
	 * @return string
	 * @throws Exception
	 * @noinspection PhpUnused
	 */
	public function getAuthorizeCodeUrl(): string
	{
		self::validParams(["appKey"]);

		$params = array(
			"client_id" => $this->appKey,
			"response_type" => "code"
		);

		if (isset($this->redirect_uri) && !empty($this->redirect_uri)) {
			$params["redirect_uri"] = $this->redirect_uri;
		}

		return "https://my.pcloud.com/oauth2/authorize?" . http_build_query($params);
	}

	/**
	 * @param string $code
	 * @param string|int $locationid
	 *
	 * @return array
	 * @throws Exception
	 * @noinspection PhpUnused
	 */
	public function getTokenFromCode(string $code, $locationid): array
	{
		self::validParams(["appKey", "appSecret"]);

		$params = array(
			"client_id" => $this->appKey,
			"client_secret" => $this->appSecret,
			"code" => $code
		);

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
			return array("access_token" => $response->access_token, "locationid" => $response->locationid);
		} else {
			throw new Exception($response->error);
		}
	}

	/**
	 * Sets cURL function execution timeout
	 *
	 * @param int $timeout
	 * @return void
	 * @noinspection PhpUnused
	 */
	public function setCurlExecutionTimeout(int $timeout) {
		$this->curl_exec_timeout = abs($timeout);
	}

	/**
	 * Sets cURL function execution timeout
	 *
	 * @return int
	 * @noinspection PhpUnused
	 */
	public function getCurlExecutionTimeout(): int
	{
		return $this->curl_exec_timeout;
	}

	/**
	 * Verifies is specific key exist in the class
	 *
	 * @param array $keys
	 *
	 * @return void
	 * @throws Exception
	 * @noinspection PhpUnused
	 */
	private function validParams(array $keys): void
	{
		foreach ($keys as $key) {
			if (!isset($this->{$key}) || empty($this->{$key})) {
				throw new Exception(sprintf("%s not found", $key));
			}
		}
	}
}