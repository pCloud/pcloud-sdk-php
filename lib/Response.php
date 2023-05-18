<?php

namespace pCloud\Sdk;

use stdClass;

/**
 * Class Response
 * The main purpose of this class is to organise the cURL response data into recognizable bundle
 *
 * @package pCloud\Sdk
 */
class Response
{

    /** @var mixed $response Response data. */
    private mixed $response;

    /** @var int $httpCode Http response code. */
    private int $httpCode;

    /** @var string $contentType Content type of the response. */
    private string $contentType;

    /**
     * Class constructor.
     *
     * @param mixed $response Response data.
     * @param int $httpCode HTTP Response code.
     * @param string $contentType
     */
    function __construct(mixed $response, int $httpCode, string $contentType)
    {
        $this->response = $response;
        $this->httpCode = $httpCode;
        $this->contentType = $contentType;

        $this->parseJson();
    }

    /**
     * Get method.
     *
     * @return stdClass
     * @throws Exception
     */
    public function get(): stdClass
    {
        if ($this->httpCode != 200) {
            throw new Exception("HTTP Code = " . $this->httpCode);
        }

        if ($this->response->result == 0) {
            return $this->parseResponse();
        } else {
            throw new Exception($this->response->error);
        }
    }

    /**
     * Converts the response if it is in JSON format.
     *
     * @return void
     */
    private function parseJson(): void
    {
        if (str_contains($this->contentType, "application/json")) {
            $this->response = json_decode($this->response);
        }
    }

    /**
     * Parse the response data.
     *
     * @return stdClass
     */
    private function parseResponse(): stdClass
    {
        $return = new stdClass();
        foreach ($this->response as $key => $value) {
            if ($key == "result") continue;
            $return->{$key} = $value;
        }
        return $return;
    }
}