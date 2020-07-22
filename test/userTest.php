<?php

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase {

	public function setUp() {
		$access_token = "ACCESS_TOKEN";
		$locationid = 1;

		$this->pCloudApp = new pCloud\App();
		$this->pCloudApp->setAccessToken($access_token);
		$this->pCloudApp->setLocationId($locationid);

		pCloud\Config::$curllib = "pCloud\TestCurl";
		$this->instance = new pCloud\User($this->pCloudApp);
	}

	public function tearDown() {
		unset($this->instance);
	}

	public function testUserInfo() {
		$expected = pCloud\Config::getApiHostByLocationId($this->pCloudApp->getLocationId()) . "userinfo";
		$query = $this->instance->getUserInfo();

		$this->assertEquals($expected, $query);
	}
}