<?php

namespace pCloud;

use InvalidArgumentException;

class File {

	private $partSize;
	private $request;

	function __construct(App $app) {
		$this->request = new Request($app);
		$this->partSize = Config::$filePartSize;
	}

	public function getLink($fileId) {
		if (!is_int($fileId)) {
			throw new InvalidArgumentException("Invalid file id");
		}

		$params = array(
			"fileid" => $fileId
		);

		$response = $this->request->get("getfilelink", $params);

		return is_object($response) ? "https://".$response->hosts[0].$response->path : $response;
	}

	public function download($fileId, $destination = "") {
		if (!is_int($fileId)) {
			throw new InvalidArgumentException("Invalid file id");
		}

		$fileLink = $this->getLink($fileId);

		if (!empty($destination)) {
			$destination = str_replace(array("\\", "/"), DIRECTORY_SEPARATOR, $destination).DIRECTORY_SEPARATOR;
		}

		if (!empty($destination) && !is_dir($destination)) {
			if (!mkdir($destination, 0777)) {
				throw new Exception("Couldn't create destination folder");
			}
		}

        $parts = explode("/", $fileLink);
        $path = $destination.rawurldecode(end($parts));

		$source = fopen($fileLink, "rb");
		$file = fopen("{$path}.download", "wb");
		while (!feof($source)) {
			$content = fread($source, $this->partSize);
			fwrite($file, $content);
		}
		fclose($file);
		fclose($source);

		return rename("{$path}.download", $path);
	}

	private function createUpload() {
		return $this->request->get("upload_create");
	}

	private function save($uploadId, $name, $folderId) {
		$params = array(
			"uploadid" => $uploadId,
			"name" => $name,
			"folderid" => $folderId
		);

		return $this->request->get("upload_save", $params);
	}

	private function write($params, $content) {
		return $this->request->put("upload_write", $params, $content);
	}

	public function upload($path, $folderId = 0, $filename = null) {
		if (!file_exists($path) || !is_file($path) || !is_readable($path)) {
			throw new Exception("Invalid file");
		}

		$path = str_replace(array("\\", "_"), "/", $path);
		$parts = explode("/", $path);

		if (!$filename) {
			$filename = end($parts);
		}

		$upload = $this->createUpload();

		$params = array(
			"uploadid" => $upload->uploadid,
			"uploadoffset" => 0
		);

		$file = fopen($path, "r");
		while (!feof($file)) {
			$content = fread($file, $this->partSize);
			$this->write($params, $content);
			$params["uploadoffset"] += $this->partSize;
		}
		fclose($file);

		return $this->save($upload->uploadid, $filename, $folderId);
	}

	public function delete($fileId) {
		if (!is_int($fileId)) {
			throw new InvalidArgumentException("Invalid file id");
		}

		$params = array(
			"fileid" => $fileId
		);

		$response = $this->request->get("deletefile", $params);

		return is_object($response) ? $response->metadata->isdeleted : $response;
	}

	public function rename($fileId, $name) {
		if (!is_int($fileId)) {
			throw new InvalidArgumentException("Invalid file id");
		}

		if (!is_string($name) || strlen($name) < 1) {
			throw new InvalidArgumentException("Invalid file name");
		}

		$params = array(
			"fileid" => $fileId,
			"toname" => $name
		);

		return $this->request->get("renamefile", $params);
	}

	public function move($fileId, $folderId) {
		if (!is_int($fileId)) {
			throw new InvalidArgumentException("Invalid file id");
		}

		if (!is_int($folderId)) {
			throw new InvalidArgumentException("Invalid folder id");
		}

		$params = array(
			"fileid" => $fileId,
			"tofolderid" => $folderId
		);

		return $this->request->get("renamefile", $params);
	}

	public function copy($fileId, $folderId) {
		if (!is_int($fileId)) {
			throw new InvalidArgumentException("Invalid file id");
		}

		if (!is_int($folderId)) {
			throw new InvalidArgumentException("Invalid folder id");
		}

		$params = array(
			"fileid" => $fileId,
			"tofolderid" => $folderId
		);

		return $this->request->get("copyfile", $params);
	}

	public function getInfo($fileId) {
		if (!is_int($fileId)) {
			throw new InvalidArgumentException("Invalid file id");
		}

		$params = array(
			"fileid" => $fileId
		);

		return $this->request->get("checksumfile", $params);
	}
}