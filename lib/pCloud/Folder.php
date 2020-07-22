<?php

namespace pCloud;

use InvalidArgumentException;

class Folder {

	private $request;

	function __construct(App $app) {
		$this->request = new Request($app);
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

    public function search(/*string*/ $path) {

        $path = pathinfo($path, PATHINFO_DIRNAME);
        //$path = str_replace(DIRECTORY_SEPARATOR,"\\",$path);

        echo "searching for folder: {$path}".PHP_EOL;

        $params = array(
            "nofiles" => 1,
            "path" => $path
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
    public function listFolder($folder = null) {

        // first compare with each folder in root
        if(is_null($folder)) {
            return (array)$this->getContent((int)0);
        }

        $extension = (string)pathinfo($folder, PATHINFO_EXTENSION);
        if(!empty($extension)) {
            $folder = (string)pathinfo($folder, PATHINFO_DIRNAME);
        }
        $path_parts = array_reverse((array)explode(DIRECTORY_SEPARATOR, $folder));

        $directory = null;
        $currentFolderId = 0;
        while(($folderName = array_pop($path_parts))){ /* current directory */
            $folderItems = (array)$this->getContent((int)$currentFolderId);

            foreach($folderItems as $key => $directory) {

                $directory = (array)$directory;
                if (isset($directory['isfolder']) && (bool)$directory['isfolder'] === true) {
                    if (!empty($directory['name']) && strncmp($folderName, $directory['name'], strlen($folderName)) === 0) {
                        $currentFolderId = $directory['folderid'];
                        break;
                    }
                }
            }

        }

        return $directory;
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