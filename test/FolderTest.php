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
		$locationId = intval(getenv('LOCATION'));

		$this->pCloudApp = new pCloud\Sdk\App();
		$this->pCloudApp->setAccessToken($access_token);
		$this->pCloudApp->setLocationId($locationId);

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
     * List root folder content.
     *
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
     * List folder content.
     *
	 * @return void
	 * @throws \pCloud\Sdk\Exception
	 */
	public function testListFolder()
	{
        $folderId = 1234;

		$expected = $this->buildExpected("listfolder", array("folderid" => $folderId));
		$query = $this->instance->getContent($folderId);

		$this->assertEquals($expected, $query);
	}

	/**
     * List folder by name.
     *
	 * @return void
	 * @throws \pCloud\Sdk\Exception
	 */
	public function testListFolderByName()
	{
		$folderName = "folderName";

		$expected = [$this->buildExpected("listfolder", array("folderid" => "0"))];

		$query = $this->instance->listFolder($folderName);

		$this->assertEquals($expected, $query);
	}

	/**
     * Create folder.
     *
	 * @return void
	 * @throws \pCloud\Sdk\Exception
	 */
	public function testCreateFolder()
	{
		$folderId = 1234;
		$name = "folderName";

		$expected = $this->buildExpected("createfolder", array("name" => $name, "folderid" => $folderId));
		$query = $this->instance->create($name, $folderId);

		$this->assertEquals($expected, $query);
	}

	/**
     * Rename folder.
     *
	 * @return void
	 * @throws \pCloud\Sdk\Exception
	 */
	public function testRenameFolder()
	{
		$folderId = 1234;
		$name = "newName";

		$expected = $this->buildExpected("renamefolder", array("toname" => $name, "folderid" => $folderId));
		$query = $this->instance->rename($folderId, $name);

		$this->assertEquals($expected, $query);
	}

	/**
     * Move folder.
     *
	 * @return void
	 * @throws \pCloud\Sdk\Exception
	 */
	public function testMoveFolder()
	{
		$folderId = 1234;
		$destination = 12;

		$expected = $this->buildExpected("renamefolder", array("tofolderid" => $destination, "folderid" => $folderId));
		$query = $this->instance->move($folderId, $destination);

		$this->assertEquals($expected, $query);
	}

	/**
     * Delete folder.
     *
	 * @return void
	 * @throws \pCloud\Sdk\Exception
	 */
	public function testDeleteFolder()
	{
        $folderId = 1234;

		$expected = $this->buildExpected("deletefolder", array("folderid" => $folderId));
		$query = $this->instance->delete($folderId);

		$this->assertEquals($expected, $query);
	}

	/**
     * Delete recursive folder.
     *
	 * @return void
	 * @throws \pCloud\Sdk\Exception
	 */
	public function testDeleteRecursiveFolder()
	{
        $folderId = 1234;

		$expected = $this->buildExpected("deletefolderrecursive", array("folderid" => $folderId));
		$query = $this->instance->deleteRecursive($folderId);

		$this->assertEquals($expected, $query);
	}

	/**
     * Build the method with the params.
     *
	 * @param string $method
	 * @param array $params
	 *
	 * @return string
	 */
	private function buildExpected(string $method, array $params): string
	{
		$expected = pCloud\Sdk\Config::getApiHostByLocationId($this->pCloudApp->getLocationId()) . $method;

		if (!empty($params)) {
			$expected .= "?" . http_build_query($params);
		}

		return $expected;
	}
}