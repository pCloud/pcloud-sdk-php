<?php

use PHPUnit\Framework\TestCase;

class FileTest extends TestCase {

	public function setUp() {
		pCloud\Config::$curllib = "pCloud\TestCurl";
		$credentialPath = dirname(__FILE__)."/../lib/pCloud/app.cred";
		pCloud\Config::$credentialPath = $credentialPath;
		$this->instance = new pCloud\File();
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

	private function buildExpected($method, $params) {
		$expected = pCloud\Config::$host.$method;

		if (!empty($params)) {
			$expected .= "?".http_build_query($params);
		}

		return $expected;
	}
}