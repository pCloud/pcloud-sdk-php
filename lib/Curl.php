<?php

namespace pCloud\Sdk;

use CurlHandle;
use InvalidArgumentException;
use Throwable;
use stdClass;

/**
 * cURL class
 * This class manages the main cURL functionality.
 *
 * @package pCloud\Sdk
 */
class Curl
{
	/** @var CurlHandle|null $curl Holds the cURL. */
	protected ?CurlHandle $curl = null;

	/** @var array $headers Array of header data. */
	private array $headers = array();

	/**
	 * Main class constructor.
     *
	 * @param string $url Class constructor.
     * @throws InvalidArgumentException If the URL is empty ( not provided ) we will throw an exception.
	 */
	function __construct(string $url)
	{
		if (empty($url)) {
			throw new InvalidArgumentException("Invalid URL");
		}

		$curlInit = curl_init($url);

        if (!is_bool($curlInit)) {
            $this->curl = $curlInit;
        }
	}

	/**
	 * Class destructor.
     * We will close the curl connection if it's not null.
	 */
	public function __destruct()
	{
		if (!is_null($this->curl)) {
            curl_close($this->curl);
		}
	}

	/**
	 * Set cURL option.
	 *
	 * @param string $option Option name.
	 * @param mixed $value Option value.
	 * @return void
	 * @noinspection PhpUnused
	 */
	public function setOption(string $option, mixed $value): void
	{
		curl_setopt($this->curl, $option, $value);
	}

	/**
	 * Add additional header to be sent with the cURL request.
	 *
	 * @param string $header Additional header string.
	 * @return void
	 * @noinspection PhpUnused
	 */
	public function addHeader(string $header): void
	{
		$this->headers[] = $header;
	}

	/**
	 * The main method, executes cURL exec and throws exception if anything fails.
	 *
	 * @return stdClass
	 * @throws Exception Throws exception if the connection is lost.
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
	 * Simple "retry" function based on the response status code.
	 *
	 * @return bool|string
	 */
	private function curlReconnect(): bool|string
    {
		try {
			foreach (range(0, 3) as $ignored) {
				$response = curl_exec($this->curl);
				if (intval(curl_getinfo($this->curl, CURLINFO_RESPONSE_CODE)) == 200) {
					return $response;
				}
                sleep(2 + (2 * $ignored));
			}
			return false;
		} catch (Throwable) {
			return false;
		}
	}
}