<?php

use PHPUnit\Framework\TestCase;

class ExceptionTest extends TestCase {

	/**
	 * @dataProvider provideCreateData
	 * @expectedException Exception
	 */

	public function testCreateFolder($name) {
		$folder = new pCloud\Folder();
		$folder->create($name);
	}

	/**
	 * @dataProvider provideRenameData
	 * @expectedException Exception
	 */

	public function testRenameFolder($folderId, $name) {
		$folder = new pCloud\Folder();
		$folder->rename($folderId, $name);
	}

	/**
	 * @dataProvider provideMoveData
	 * @expectedException Exception
	 */

	public function testMoveFolder($folderId, $newParent) {
		$folder = new pCloud\Folder();
		$folder->move($folderId, $newParent);
	}

	/**
	 * @dataProvider provideId
	 * @expectedException Exception
	 */

	public function testDeleteFolder($folderId) {
		$folder = new pCloud\Folder();
		$folder->delete($folderId);
	}

	/**
	 * @dataProvider provideId
	 * @expectedException Exception
	 */

	public function testDeleteRecursiveFolder($folderId) {
		$folder = new pCloud\Folder();
		$folder->deleteRecursive($folderId);
	}

	/**
	 * @dataProvider provideId
	 * @expectedException Exception
	 */

	public function testGetFileLink($fileId) {
		$file = new pCloud\File();
		$file->getLink($fileId);
	}

	/**
	 * @dataProvider provideDownloadData
	 * @expectedException Exception
	 */

	public function testDownloadFile($fileId, $destination) {
		$file = new pCloud\File();
		$file->download($fileId, $destination);
	}

	/**
	 * @dataProvider provideUploadData
	 * @expectedException Exception
	 */

	public function testUploadFile($path, $folderId) {
		$file = new pCloud\File();
		$file->upload($path, $folderId);
	}

	/**
	 * @dataProvider provideId
	 * @expectedException Exception
	 */

	public function testDeleteFile($fileId) {
		$file = new pCloud\File();
		$file->delete($fileId);
	}

	/**
	 * @dataProvider provideMoveData
	 * @expectedException Exception
	 */

	public function testMoveFile($fileId, $folderId) {
		$file = new pCloud\File();
		$file->move($fileId, $folderId);
	}

	/**
	 * @dataProvider provideRenameData
	 * @expectedException Exception
	 */

	public function testRenameFile($fileId, $name) {
		$file = new pCloud\File();
		$file->rename($fileId, $name);
	}

	/**
	 * @dataProvider provideMoveData
	 * @expectedException Exception
	 */

	public function testCopyFile($fileId, $folderId) {
		$file = new pCloud\File();
		$file->copy($fileId, $folderId);
	}

	/**
	 * @dataProvider provideId
	 * @expectedException Exception
	 */

	public function testGetFileInfo($fileId) {
		$file = new pCloud\File();
		$file->getInfo($fileId);
	}

	public function provideId() {
		return array(
			array(false),
			array(1)
		);
	}

	public function provideRenameData() {
		return array(
			array(0, ""),
			array(0, "invalid/name"),
			array(0, false),
			array(0, true),
			array(0, null),
			array(1, ""),
			array(1, "New name"),
			array(false, "New name"),
		);
	}

	public function provideCreateData() {
		return array(
			array(""),
			array("invalid/name"),
			array(false),
			array(true),
			array(null)
		);
	}

	public function provideMoveData() {
		return array(
			array(0, 1),
			array(1, 0),
			array(0, false),
			array(0, true),
			array(0, null)
		);
	}

	public function provideUploadData() {
		return array(
			array(dirname(__FILE__).DIRECTORY_SEPARATOR."office pic 2015-09-28 16-44-30.jpeg", null),
			array("not existing file", 0),
			array(dirname(__FILE__).DIRECTORY_SEPARATOR."office pic 2015-09-28 16-44-30.jpeg", 1),
			array(dirname(__FILE__).DIRECTORY_SEPARATOR, 0),
			array(false, 0)
		);
	}

	public function provideDownloadData() {
		return array(
			array(false, dirname(__FILE__)),
			array(null, dirname(__FILE__)),
			array(1, ""),
			array(1, null),
			array(0, false)
		);
	}
}