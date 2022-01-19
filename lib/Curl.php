<?php

namespace pCloud\Sdk;

use InvalidArgumentException;
use Throwable;
use stdClass;

/**
 * cURL class
 * this class manages the main cURL functionality
 *
 * @package pCloud\Sdk
 */
class Curl
{
	/**
	 * Holds the cURL
	 *
	 * @var false|resource $curl
	 */
	protected $curl;

	/**
	 * Array of header data
	 *
	 * @var array $headers
	 */
	private $headers = array();

	/**
	 * Class constructor
	 *
	 * @param string $url
	 */
	function __construct(string $url)
	{
		if (empty($url)) {
			throw new InvalidArgumentException("Invalid URL");
		}

		$this->curl = curl_init($url);
	}

	/**
	 * Class destructor
	 */
	public function __destruct()
	{
		if (is_resource($this->curl)) {
			curl_close($this->curl);
		}
	}

	/**
	 * Set cURL option
	 *
	 * @param string $option
	 * @param mixed $value
	 *
	 * @return void
	 * @noinspection PhpUnused
	 */
	public function setOption(string $option, $value): void
	{
		curl_setopt($this->curl, $option, $value);
	}

	/**
	 * Add aditional header to be sent with the cURL request
	 *
	 * @param string $header
	 *
	 * @return void
	 * @noinspection PhpUnused
	 */
	public function addHeader(string $header): void
	{
		$this->headers[] = $header;
	}

	/**
	 * The main method, executes cURL exec and throws exception if anything fails
	 *
	 * @return stdClass
	 *
	 * @throws Exception
	 * @noinspection PhpUnused
	 */
	public function exec(): stdClass
	{
		$this->setOption(CURLOPT_HEADER, $this->headers);

		$r = $this->curlReconnect();

		$httpCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
		$contentType = curl_getinfo($this->curl, CURLINFO_CONTENT_TYPE);

		if (!is_bool($r)) {
			$response = new Response($r, $httpCode, $contentType);
			return $response->get();
		} else {
			throw new Exception("Connection lost!");
		}
	}

	/**
	 * Simple "retry" function based on the response status code
	 *
	 * @return bool|string
	 */
	private function curlReconnect()
	{
		try {
			foreach (range(0, 3) as $ignored) {
				$response = curl_exec($this->curl);
				if (intval(curl_getinfo($this->curl, CURLINFO_RESPONSE_CODE)) == 200) {
					return $response;
				}
			}
			return false;
		} catch (Throwable $e) {
			return false;
		}
	}
}