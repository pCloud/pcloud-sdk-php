<?php

use PHPUnit\Framework\TestCase;

/**
 * File test
 */
class FileTest extends TestCase
{

	/**
     * Setup the app.
     *
	 * @return void
	 */
	public function setUp(): void
	{
		$access_token = getenv('ACCESS_TOKEN');
		$locationId = intval(getenv('LOCATION'));

		$this->pCloudApp = new pCloud\Sdk\App();
		$this->pCloudApp->setAccessToken($access_token);
		$this->pCloudApp->setLocationId($locationId);

		pCloud\Sdk\Config::$curllib = "pCloud\Sdk\TestCurl";
		$this->instance = new pCloud\Sdk\File($this->pCloudApp);
	}

	/**
	 * @return void
	 */
	public function tearDown(): void
	{
		unset($this->instance);
	}

	/**
     * Get File info.
     *
	 * @return void
	 * @throws \pCloud\Sdk\Exception
	 */
	public function testGetFileInfo()
	{
		$fileId = 1234;

		$expected = $this->buildExpected("checksumfile", array("fileid" => $fileId));
		$query = $this->instance->getInfo($fileId);

		$this->assertEquals($expected, $query);
	}

	/**
     * Get file link.
     *
	 * @throws \pCloud\Sdk\Exception
	 */
	public function testGetFileLink()
	{
        $fileId = 1234;

		$expected = $this->buildExpected("getfilelink", array("fileid" => $fileId));
		$query = $this->instance->getLink($fileId);

		$this->assertEquals($expected, $query);
	}

	/**
     * Delete file.
     *
	 * @throws \pCloud\Sdk\Exception
	 */
	public function testDeleteFile()
	{
		$fileId = 1234;

		$expected = $this->buildExpected("deletefile", array("fileid" => $fileId));
		$query = $this->instance->delete($fileId);

		$this->assertEquals($expected, $query);
	}

	/**
     * Rename file.
     *
	 * @throws \pCloud\Sdk\Exception
	 */
	public function testRenameFile()
	{
        $fileId = 1234;
		$name = "fileName";

		$expected = $this->buildExpected("renamefile", array("fileid" => $fileId, "toname" => $name));
		$query = $this->instance->rename($fileId, $name);

		$this->assertEquals($expected, $query);
	}

	/**
     * Move file.
     *
	 * @throws \pCloud\Sdk\Exception
	 */
	public function testMoveFile()
	{
        $fileId = 1234;
		$destination = 12;

		$expected = $this->buildExpected("renamefile", array("fileid" => $fileId, "tofolderid" => $destination));
		$query = $this->instance->move($fileId, $destination);

		$this->assertEquals($expected, $query);
	}

	/**
     * Copy file.
     *
	 * @throws \pCloud\Sdk\Exception
	 */
	public function testCopyFile()
	{
        $fileId = 1234;
		$destination = 12;

		$expected = $this->buildExpected("copyfile", array("fileid" => $fileId, "tofolderid" => $destination));
		$query = $this->instance->copy($fileId, $destination);

		$this->assertEquals($expected, $query);
	}

	/**
     * Download file.
     *
	 * @throws \pCloud\Sdk\Exception
	 */
	public function testDownloadFile()
	{
        $fileId = 1234;
		$destination = sys_get_temp_dir();
		if (empty($destination)) $destination = "/tmp";

		$result = $this->instance->download($fileId, $destination);

		$this->assertEquals('boolean', gettype($result));
	}

	/**
     * Build expected query.
     *
	 * @param $method
	 * @param $params
	 *
	 * @return string
	 */
	private function buildExpected($method, $params): string
	{
		$expected = pCloud\Sdk\Config::getApiHostByLocationId($this->pCloudApp->getLocationId()) . $method;

		if (!empty($params)) {
			$expected .= "?" . http_build_query($params);
		}

		return $expected;
	}
}