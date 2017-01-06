<?php

namespace pCloud;

use InvalidArgumentException;

class Folder {

	private $request;

	function __construct() {
		$this->request = new Request();
	}

	public function getMetadata($folderId) {
		if (!is_int($folderId)) {
			throw new InvalidArgumentException("Invalid folder id");
		}

		$params = array(
			"folderid" => $folderId
		);

		return $this->request->get("listfolder", $params);
	}

	public function listRoot() {
		return $this->getContent(0);
	}

	public function getContent($folderId) {
		if (!is_int($folderId)) {
			throw new InvalidArgumentException("Invalid folder id");
		}

		$folderMetadata = $this->getMetadata($folderId);
		return is_object($folderMetadata) ? $folderMetadata->metadata->contents : $folderMetadata;
	}

	public function create($name = "", $parent = 0) {
		if (!is_string($name) || strlen($name) < 1) {
			throw new InvalidArgumentException("Invalid folder name");
		}

		$params = array(
			"name" => $name,
			"folderid" => $parent
		);

		$response = $this->request->get("createfolder", $params);

		return is_object($response) ? $response->metadata->folderid : $response;
	}

	public function rename($folderId, $name) {
		if (!is_int($folderId)) {
			throw new InvalidArgumentException("Invalid folder id");
		}
		if (!is_string($name) || strlen($name) < 1) {
			throw new InvalidArgumentException("Invalid folder name");
		}

		$params = array(
			"toname" => $name,
			"folderid" => $folderId
		);

		$response = $this->request->get("renamefolder", $params);

		return is_object($response) ? $response->metadata->folderid : $response;
	}

	public function move($folderId, $newParent) {
		if (!is_int($folderId)) {
			throw new InvalidArgumentException("Invalid folder id");
		}

		if (!is_int($newParent)) {
			throw new InvalidArgumentException("Invalid new parent id");
		}

		$params = array(
			"tofolderid" => $newParent,
			"folderid" => $folderId
		);

		$response = $this->request->get("renamefolder", $params);

		return is_object($response) ? $response->metadata->folderid : $response;
	}

	public function delete($folderId) {
		if (!is_int($folderId)) {
			throw new InvalidArgumentException("Invalid folder id");
		}

		$params = array(
			"folderid" => $folderId
		);

		$response = $this->request->get("deletefolder", $params);

		return is_object($response) ? $response->metadata->isdeleted : $response;
	}

	public function deleteRecursive($folderId) {
		if (!is_int($folderId)) {
			throw new InvalidArgumentException("Invalid folder id");
		}

		$params = array(
			"folderid" => $folderId
		);

		return $this->request->get("deletefolderrecursive", $params);
	}
}