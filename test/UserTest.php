<?php

use PHPUnit\Framework\TestCase;

/**
 * User tests
 */
class UserTest extends TestCase
{

	/**
	 * @return void
	 * @throws \pCloud\Sdk\Exception
	 */
	public function setUp(): void
	{
		$access_token = getenv('ACCESS_TOKEN');
		$locationid = intval(getenv('LOCATION'));

		$this->pCloudApp = new pCloud\Sdk\App();
		$this->pCloudApp->setAccessToken($access_token);
		$this->pCloudApp->setLocationId($locationid);

		pCloud\Sdk\Config::$curllib = "pCloud\Sdk\TestCurl";
		$this->instance = new pCloud\Sdk\User($this->pCloudApp);
	}

	/**
	 * @return void
	 */
	public function tearDown(): void
	{
		unset($this->instance);
	}

	/**
	 * @return void
	 */
	public function testUserInfo()
	{

		$expected = pCloud\Sdk\Config::getApiHostByLocationId($this->pCloudApp->getLocationId()) . "userinfo";
		$query = $this->instance->getUserInfo();

		$this->assertEquals($expected, $query);
	}
}