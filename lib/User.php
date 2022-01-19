<?php

namespace pCloud\Sdk;

use stdClass;

/**
 * User class
 *
 * @package pCloud\Sdk
 */
class User
{

	/**
	 * Holds the Request class
	 *
	 * @var Request $request
	 */
	private $request;

	/**
	 * @var stdClass $userInfo
	 */
	private $userInfo;

	/**
	 * Class constructor
	 *
	 * @param App $app
	 * @throws Exception
	 */
	function __construct(App $app)
	{
		$this->request = new Request($app);
		$this->userInfo = $this->request->get("userinfo");
	}

	/**
	 * Get the full user info
	 *
	 * @return stdClass
	 * @noinspection PhpUnused
	 */
	public function getUserInfo(): stdClass
	{
		return $this->userInfo;
	}

	/**
	 * Get ther User ID
	 *
	 * @return int
	 * @noinspection PhpUnused
	 */
	public function getUserId(): int
	{
		return intval($this->userInfo->userid);
	}

	/**
	 * Get the user Email Address
	 *
	 * @return string
	 * @noinspection PhpUnused
	 */
	public function getUserEmail(): string
	{
		return strval($this->userInfo->email);
	}

	/**
	 * Get the used quota
	 *
	 * @return int
	 * @noinspection PhpUnused
	 */
	public function getUsedQuota(): int
	{
		return intval($this->userInfo->usedquota);
	}

	/**
	 * Get quota
	 *
	 * @return int
	 * @noinspection PhpUnused
	 */
	public function getQuota(): int
	{
		return intval($this->userInfo->quota);
	}

	/**
	 * Get user's public links quota
	 *
	 * @return int
	 * @noinspection PhpUnused
	 */
	public function getPublicLinkQuota(): int
	{
		return intval($this->userInfo->publiclinkquota);
	}
}