<?php

namespace pCloud\Sdk;

use InvalidArgumentException;
use stdClass;

/**
 * Folder Class
 *
 * @package pCloud\Sdk
 */
class Folder
{
	/** @var Request $request Holds the Request class. */
	private Request $request;

	/**
	 * Main class constructor.
	 *
	 * @param App $app
	 */
	function __construct(App $app)
	{
		$this->request = new Request($app);
	}

	/**
	 * Get folder meta data.
	 *
	 * @param int $folderId Folder ID.
     *
	 * @return stdClass
	 * @throws Exception
	 * @noinspection PhpUnused
	 */
	public function getMetadata(int $folderId): stdClass
	{
		return $this->request->get("listfolder", array("folderid" => $folderId));
	}

	/**
	 * Search for folder.
	 *
	 * @param string $path Folder path.
     *
	 * @return stdClass
	 * @throws Exception
	 * @noinspection PhpUnused
	 */
	public function search(string $path): stdClass
	{
		$path = pathinfo($path, PATHINFO_DIRNAME);

		$params = array(
			"nofiles" => 1,
			"path" => $path
		);

		return $this->request->get("listfolder", $params);
	}

	/**
	 * list files in folder identified by name
	 * (start from the root, match directory name, ...)
	 *
	 * @param int|string|null $folder Folder name.
	 *
	 * @return array|null
	 * @throws Exception
	 * @noinspection PhpUnused
	 */
	public function listFolder(int|string $folder = null): ?array
	{

		// first compare with each folder in root
		if (is_null($folder)) {
			return $this->getContent(0);
		}

		$extension = strval(pathinfo($folder, PATHINFO_EXTENSION));
		if (!empty($extension)) {
			$folder = strval(pathinfo($folder, PATHINFO_DIRNAME));
		}

		$path_parts = array_reverse( explode(DIRECTORY_SEPARATOR, $folder) );

		$directory = null;
		$currentFolderId = 0;

		while (($folderName = array_pop($path_parts))) { /* current directory */

			$folderItems = $this->getContent(intval($currentFolderId));

			foreach ($folderItems as $directory) {

				$directory = (array)$directory;
				if (isset($directory['isfolder']) && boolval($directory['isfolder']) === true) {
					if (!empty($directory['name']) && strncmp($folderName, $directory['name'], strlen($folderName)) === 0) {
						$currentFolderId = $directory['folderid'];
						break;
					}
				}
			}
		}

		return $directory;
	}

	/**
	 * List root folders.
	 *
	 * @return array
	 * @throws Exception
	 * @noinspection PhpUnused
	 */
	public function listRoot(): array
	{
		return $this->getContent(0);
	}

	/**
	 * Get folder content.
	 *
	 * @param int $folderId Folder ID.
     *
	 * @return array
	 * @throws Exception
	 * @noinspection PhpUnused
	 */
	public function getContent(int $folderId): array
	{
		$folderMetadata = $this->getMetadata($folderId);
        if (property_exists($folderMetadata, 'metadata')) {
            return $folderMetadata->metadata->contents;
        } else {
            $allMetadata = (array) $folderMetadata;
            return $allMetadata[0];
        }
	}

	/**
     * Create folder.
     *
	 * @param string|null $name Folder name.
	 * @param int|null $parent Parent folder ID.
	 *
	 * @return stdClass|int  Folder ID or response Data
	 * @throws Exception
	 * @noinspection PhpUnused
	 */
	public function create(?string $name, ?int $parent = 0): int|stdClass
    {
		if (empty($name)) {
			throw new InvalidArgumentException("Please, provide valid folder name");
		}

		$params = array(
			"name" => $name,
			"folderid" => $parent
		);

		$response = $this->request->get("createfolder", $params);

		return property_exists($response, 'metadata') ? $response->metadata->folderid : $response;
	}

	/**
	 * Rename folder.
	 *
	 * @param int $folderId Folder ID.
	 * @param string|null $name New folder name.
	 *
	 * @return stdClass|int
	 * @throws Exception
	 * @noinspection PhpUnused
	 */
	public function rename(int $folderId, ?string $name): int|stdClass
    {
		if (empty($name)) {
			throw new InvalidArgumentException("Please, provide folder name");
		}

		$params = array(
			"toname" => $name,
			"folderid" => $folderId
		);

		$response = $this->request->get("renamefolder", $params);

		return property_exists($response, 'metadata') ? $response->metadata->folderid : $response;
	}

	/**
	 * Move folder.
	 *
	 * @param int $folderId Folder ID.
	 * @param int $newParent New parent folder ID.
	 *
	 * @return stdClass|int
	 * @throws Exception
	 * @noinspection PhpUnused
	 */
	public function move(int $folderId, int $newParent): int|stdClass
    {
		$params = array(
			"tofolderid" => $newParent,
			"folderid" => $folderId
		);

		$response = $this->request->get("renamefolder", $params);

		return property_exists($response, 'metadata') ? $response->metadata->folderid : $response;
	}

	/**
	 * Delete folder.
	 *
	 * @param int $folderId Folder ID.
     *
	 * @return stdClass
	 * @throws Exception
	 * @noinspection PhpUnused
	 */
	public function delete(int $folderId): stdClass
	{
		$response = $this->request->get("deletefolder", array("folderid" => $folderId));

		return property_exists($response, 'metadata') ? $response->metadata->isdeleted : $response;
	}

	/**
	 * Delete recursive.
	 *
	 * @param int $folderId Folder ID.
     *
	 * @return stdClass
	 * @throws Exception
	 * @noinspection PhpUnused
	 */
	public function deleteRecursive(int $folderId): stdClass
	{
		return $this->request->get("deletefolderrecursive", array("folderid" => $folderId));
	}
}
