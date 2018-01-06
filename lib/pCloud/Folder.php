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

    /**
     * list files in folder identified by name
     * (start from the root, match directory name, ..)
     *
     * @param int|string $folder
     * @return array|null
     */
    public function listFolder($folder = 0) {

        if(is_numeric($folder)) {
            return (array)$this->getContent((int)$folder);
        }

        $extension = (string)pathinfo($folder, PATHINFO_EXTENSION);
        if(!empty($extension)) {
            $folder = (string)pathinfo($folder, PATHINFO_DIRNAME);
        }
        $path_parts = (array)explode(DIRECTORY_SEPARATOR, $folder);

        $currentDirectory = is_numeric($folder) ? (array)$this->getContent($folder) : [];
        $i = 0;
        foreach($path_parts as $folderName) {

            while ($currentDirectory) {
                $directory = (array)array_pop($currentDirectory);

                if (isset($directory['isfolder']) && (bool)$directory['isfolder'] === true) {
                    if (!empty($directory['name']) && strncmp($folderName, $directory['name'], strlen($folderName)) === 0) {

                        if(count($path_parts) === ++$i) {
                            return (array)$directory;
                        }
                        $currentDirectory = (array)$this->getContent($directory['folderid']);
                        break;
                    }
                }
            }
        }

        return null;
    }


	public function listRoot() {
		return $this->getContent(0);
	}

	public function getContent($folderId) {
		if (!is_int($folderId)) {
			throw new InvalidArgumentException("Invalid folder id {$folderId}");
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