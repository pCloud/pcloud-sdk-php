<?php

namespace pCloud\Sdk;

/**
 * Local Exception handler.
 * Overwrites the error code to 5000+
 *
 * @package pCloud\Sdk
 */
class Exception extends \Exception
{

	/**
	 * Class constructor.
	 *
	 * @param string $message Exception message.
	 * @param $cause
	 */
	function __construct(string $message, $cause = null)
	{
		parent::__construct($message, 5000, $cause);
	}
}
