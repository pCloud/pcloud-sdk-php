<?php

use PHPUnit\Framework\TestCase;

class FileTest extends TestCase {

	public function setUp() {
		$access_token = "ACCESS_TOKEN";
		$locationid = 1;

		$this->pCloudApp = new pCloud\App();
		$this->pCloudApp->setAccessToken($access_token);
		$this->pCloudApp->setLocationId($locationid);

		pCloud\Config::$curllib = "pCloud\TestCurl";
		$this->instance = new pCloud\File($this->pCloudApp);
	}

	public function tearDown() {
		unset($this->instance);
	}

	public function testGetFileInfo() {
		$fileid = 1234;

		$expected = $this->buildExpected("checksumfile", array("fileid" => $fileid));
		$query = $this->instance->getInfo($fileid);

		$this->assertEquals($expected, $query);
	}

	public function testGetFileLink() {
		$fileid = 1234;

		$expected = $this->buildExpected("getfilelink", array("fileid" => $fileid));
		$query = $this->instance->getLink($fileid);

		$this->assertEquals($expected, $query);
	}

	public function testDeleteFile() {
		$fileid = 1234;

		$expected = $this->buildExpected("deletefile", array("fileid" => $fileid));
		$query = $this->instance->delete($fileid);

		$this->assertEquals($expected, $query);
	}

	public function testRenameFile() {
		$fileid = 1234;
		$name = "fileName";

		$expected = $this->buildExpected("renamefile", array("fileid" => $fileid, "toname" => $name));
		$query = $this->instance->rename($fileid, $name);

		$this->assertEquals($expected, $query);
	}

	public function testMoveFile() {
		$fileid = 1234;
		$destination = 12;

		$expected = $this->buildExpected("renamefile", array("fileid" => $fileid, "tofolderid" => $destination));
		$query = $this->instance->move($fileid, $destination);

		$this->assertEquals($expected, $query);
	}

	public function testCopyFile() {
		$fileid = 1234;
		$destination = 12;

		$expected = $this->buildExpected("copyfile", array("fileid" => $fileid, "tofolderid" => $destination));
		$query = $this->instance->copy($fileid, $destination);

		$this->assertEquals($expected, $query);
	}

	public function testDownloadFile() {
        $fileid = 1234;
        $destination = sys_get_temp_dir();

        $result = $this->instance->download($fileid, $destination);

        $this->assertEquals(gettype($result), 'boolean');
    }

	private function buildExpected($method, $params) {
		$expected = pCloud\Config::getApiHostByLocationId($this->pCloudApp->getLocationId()) . $method;

		if (!empty($params)) {
			$expected .= "?".http_build_query($params);
		}

		return $expected;
	}
}