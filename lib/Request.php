<?php

namespace pCloud\Sdk;

use stdClass;

/**
 * Class Request
 *
 * @package pCloud\Sdk
 */
class Request
{

	/**
	 * pCloud api endpoint host
	 *
	 * @var string $host
	 */
	private $host;

	/**
	 * Global parameters, initially access token is in this variable,
	 * later additional parameters are applied
	 *
	 * @var array $globalParams
	 */
	private $globalParams;

	/**
	 * cUrl connection is in this variable.
	 *
	 * @var Curl $curlConn
	 */
	private $curlConn = null;

	/**
	 * Here will be located the final cUrl URL endpoint
	 *
	 * @var string $curlEndPoint
	 */
	private $curlEndPoint = '';

	/**
	 * cURL function execution timeout ( in seconds )
	 *
	 * @var int $curlExecutionTimeout
	 */
	private $curlExecutionTimeout;

	/**
	 * Class constructor
	 * collects the host and the initial global parameters
	 *
	 * @param App $app
	 */
	function __construct(App $app)
	{
		$this->host = Config::getApiHostByLocationId($app->getLocationId());
		$this->globalParams = array(
			"access_token" => $app->getAccessToken()
		);

		$this->curlExecutionTimeout = $app->getCurlExecutionTimeout();
	}

	/**
	 * GET method
	 *
	 * @param string $method
	 * @param array|null $params
	 *
	 * @return stdClass
	 * @throws Exception
	 * @noinspection PhpUnused
	 */
	public function get(string $method, ?array $params = array()): stdClass
	{
		if (!is_null($this->curlConn)) {
			$this->curlConn->__destruct();
			$this->curlConn = null;
		}

		$this->curlEndPoint = $this->_prepareURL($method, array_merge($this->globalParams, $params));
		$this->_buildCurl();

		return $this->curlConn->exec();
	}

	/**
	 * Post method
	 *
	 * @param string $method
	 * @param array|null $params
	 *
	 * @return stdClass
	 * @throws Exception
	 * @noinspection PhpUnused
	 */
	public function post(string $method, ?array $params = array()): stdClass
	{
		if (!is_null($this->curlConn)) {
			$this->curlConn->__destruct();
			$this->curlConn = null;
		}

		$this->curlEndPoint = $this->_prepareURL($method, $this->globalParams);
		$this->_buildCurl();

		if (!is_null($this->curlConn)) {
			$this->curlConn->setOption(CURLOPT_CUSTOMREQUEST, "POST");
			$this->curlConn->setOption(CURLOPT_POSTFIELDS, $params);
		}

		return $this->curlConn->exec();
	}

	/**
	 * Put method, will mostly be used for file upload
	 *
	 * @param string $method
	 * @param string $content
	 * @param array|null $params
	 *
	 * @return stdClass
	 * @throws Exception
	 * @noinspection PhpUnused
	 */
	public function put(string $method, string $content, ?array $params = array()): stdClass
	{

		if (!is_null($this->curlConn)) {
			$this->curlConn->__destruct();
			$this->curlConn = null;
		}

		$this->curlEndPoint = $this->_prepareURL($method, array_merge($this->globalParams, $params));
		$this->_buildCurl();

		$this->curlConn->setOption(CURLOPT_CUSTOMREQUEST, "PUT");
		$this->curlConn->setOption(CURLOPT_POSTFIELDS, $content);
		$this->curlConn->setOption(CURLOPT_HTTPHEADER, array("Content-Type: text/html"));
		$this->curlConn->setOption(CURLOPT_BINARYTRANSFER, true); // TODO: deprecated !

		return $this->curlConn->exec();
	}

	/**
	 * Setup new Curl instance
	 */
	private function _buildCurl()
	{
		$this->curlConn = new Config::$curllib($this->curlEndPoint);

		$this->curlConn->setOption(CURLOPT_USERAGENT, "pCloud PHP SDK");
		$this->curlConn->setOption(CURLOPT_SSL_VERIFYPEER, false);
		$this->curlConn->setOption(CURLOPT_RETURNTRANSFER, true);

		// The number of seconds to wait while trying to connect. Use 0 to wait indefinitely.
		$this->curlConn->setOption(CURLOPT_CONNECTTIMEOUT, 20);

		// The maximum number of seconds to allow cURL functions to execute.
		$this->curlConn->setOption(CURLOPT_TIMEOUT, $this->curlExecutionTimeout);
	}

	/**
	 * This method combines the host with the API method and if there are some additional parameters.
	 *
	 * @param string $method
	 *
	 * @param string|array|null $params
	 * @return string
	 */
	private function _prepareURL(string $method, $params = null): string
	{
		$url = $this->host . $method;

		if (!empty($params)) {
			$url .= "?" . http_build_query($params);
		}

		return $url;
	}
}