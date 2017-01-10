<?php

use PHPUnit\Framework\TestCase;

class FolderTest extends TestCase {

	public function setUp() {
		$credentialPath = dirname(__FILE__)."/../lib/pCloud/app.cred";
		pCloud\Config::$credentialPath = $credentialPath;
		pCloud\Config::$curllib = "pCloud\TestCurl";
		$this->instance = new pCloud\Folder();
	}

	public function tearDown() {
		unset($this->instance);
	}

	public function testListRoot() {
		$expected = $this->buildExpected("listfolder", array("folderid" => 0));
		$query = $this->instance->listRoot();

		$this->assertEquals($expected, $query);
	}

	public function testListFolder() {
		$folderid = 1234;

		$expected = $this->buildExpected("listfolder", array("folderid" => $folderid));
		$query = $this->instance->getContent($folderid);

		$this->assertEquals($expected, $query);
	}

	public function testCreateFolder() {
		$folderid = 1234;
		$name = "folderName";

		$expected = $this->buildExpected("createfolder", array("name" => $name, "folderid" => $folderid));
		$query = $this->instance->create($name, $folderid);

		$this->assertEquals($expected, $query);
	}

	public function testRenameFolder() {
		$folderid = 1234;
		$name = "newName";

		$expected = $this->buildExpected("renamefolder", array("toname" => $name, "folderid" => $folderid));
		$query = $this->instance->rename($folderid, $name);

		$this->assertEquals($expected, $query);
	}

	public function testMoveFolder() {
		$folderid = 1234;
		$destination = 12;

		$expected = $this->buildExpected("renamefolder", array("tofolderid" => $destination, "folderid" => $folderid));
		$query = $this->instance->move($folderid, $destination);

		$this->assertEquals($expected, $query);
	}

	public function testDeleteFolder() {
		$folderid = 1234;

		$expected = $this->buildExpected("deletefolder", array("folderid" => $folderid));
		$query = $this->instance->delete($folderid);

		$this->assertEquals($expected, $query);
	}

	public function testDeleteRecursiveFolder() {
		$folderid = 1234;

		$expected = $this->buildExpected("deletefolderrecursive", array("folderid" => $folderid));
		$query = $this->instance->deleteRecursive($folderid);

		$this->assertEquals($expected, $query);
	}

	private function buildExpected($method, $params) {
		$expected = pCloud\Config::$host.$method;

		if (!empty($params)) {
			$expected .= "?".http_build_query($params);
		}

		return $expected;
	}
}