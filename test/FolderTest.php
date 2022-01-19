<?php

use PHPUnit\Framework\TestCase;

/**
 * Folders tests
 */
class FolderTest extends TestCase
{

	/**
	 * @return void
	 */
	public function setUp(): void
	{
		$access_token = getenv('ACCESS_TOKEN');
		$locationid = intval(getenv('LOCATION'));

		$this->pCloudApp = new pCloud\Sdk\App();
		$this->pCloudApp->setAccessToken($access_token);
		$this->pCloudApp->setLocationId($locationid);

		pCloud\Sdk\Config::$curllib = "pCloud\Sdk\TestCurl";
		$this->instance = new pCloud\Sdk\Folder($this->pCloudApp);
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
	 * @throws \pCloud\Sdk\Exception
	 */
	public function testListRoot()
	{
		$expected = $this->buildExpected("listfolder", array("folderid" => 0));
		$query = $this->instance->listRoot();

		$this->assertEquals($expected, $query);
	}

	/**
	 * @return void
	 * @throws \pCloud\Sdk\Exception
	 */
	public function testListFolder()
	{
		$folderid = 1234;

		$expected = $this->buildExpected("listfolder", array("folderid" => $folderid));
		$query = $this->instance->getContent($folderid);

		$this->assertEquals($expected, $query);
	}

	/**
	 * @return void
	 * @throws \pCloud\Sdk\Exception
	 */
	public function testlistFolderByName()
	{
		$folderName = "folderName";

		$expected = [$this->buildExpected("listfolder", array("folderid" => "0"))];

		$query = $this->instance->listFolder($folderName);

		$this->assertEquals($expected, $query);
	}

	/**
	 * @return void
	 * @throws \pCloud\Sdk\Exception
	 */
	public function testCreateFolder()
	{
		$folderid = 1234;
		$name = "folderName";

		$expected = $this->buildExpected("createfolder", array("name" => $name, "folderid" => $folderid));
		$query = $this->instance->create($name, $folderid);

		$this->assertEquals($expected, $query);
	}

	/**
	 * @return void
	 * @throws \pCloud\Sdk\Exception
	 */
	public function testRenameFolder()
	{
		$folderid = 1234;
		$name = "newName";

		$expected = $this->buildExpected("renamefolder", array("toname" => $name, "folderid" => $folderid));
		$query = $this->instance->rename($folderid, $name);

		$this->assertEquals($expected, $query);
	}

	/**
	 * @return void
	 * @throws \pCloud\Sdk\Exception
	 */
	public function testMoveFolder()
	{
		$folderid = 1234;
		$destination = 12;

		$expected = $this->buildExpected("renamefolder", array("tofolderid" => $destination, "folderid" => $folderid));
		$query = $this->instance->move($folderid, $destination);

		$this->assertEquals($expected, $query);
	}

	/**
	 * @return void
	 * @throws \pCloud\Sdk\Exception
	 */
	public function testDeleteFolder()
	{
		$folderid = 1234;

		$expected = $this->buildExpected("deletefolder", array("folderid" => $folderid));
		$query = $this->instance->delete($folderid);

		$this->assertEquals($expected, $query);
	}

	/**
	 * @return void
	 * @throws \pCloud\Sdk\Exception
	 */
	public function testDeleteRecursiveFolder()
	{
		$folderid = 1234;

		$expected = $this->buildExpected("deletefolderrecursive", array("folderid" => $folderid));
		$query = $this->instance->deleteRecursive($folderid);

		$this->assertEquals($expected, $query);
	}

	/**
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