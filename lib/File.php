<?php

namespace pCloud\Sdk;

use stdClass;

/**
 * Class File
 *
 * this class provides API calls related to file manipulation
 * @package pCloud\Sdk
 */
class File
{

	/**
	 * The size in bytes of each uploaded/downloaded chunk
	 *
	 * @var int $partSize
	 */
	private $partSize;

	/**
	 * Holds the Request class
	 *
	 * @var Request $request
	 */
	private $request;

	/**
	 * File Class constructor
	 *
	 * @param App $app
	 */
	function __construct(App $app)
	{
		$this->request = new Request($app);
		$this->partSize = Config::$filePartSize;
	}

	/**
	 * Get link ( using File ID )
	 *
	 * @param int $fileId
	 *
	 * @return string
	 * @throws Exception
	 * @noinspection PhpUnused
	 */
	public function getLink(int $fileId): string
	{
		$params = array(
			"fileid" => $fileId
		);

		$response = $this->request->get("getfilelink", $params);

		if (property_exists($response, 'hosts')) {
			$link = "https://" . $response->hosts[0] . $response->path;
		} else {
			throw new Exception("Failed to get file link!");
		}

		return $link;
	}

	/**
	 * Download file
	 *
	 * @param int $fileId File ID
	 * @param string $destination The destination, where the file will be stored!
	 *
	 * @return bool
	 * @throws Exception
	 * @noinspection PhpUnused
	 */
	public function download(int $fileId, string $destination = ""): bool
	{
		$fileLink = $this->getLink($fileId);

		if (!empty($destination)) {
			$destination = str_replace(array("\\", "/"), DIRECTORY_SEPARATOR, $destination) . DIRECTORY_SEPARATOR;
		}

		if (!empty($destination) && !is_dir($destination)) {
			if (!mkdir($destination)) {
				throw new Exception("Couldn't create destination folder");
			}
		}

		$parts = explode("/", $fileLink);
		$path = $destination . rawurldecode(end($parts));

		$source = fopen($fileLink, "rb");
		$file = fopen($path . ".download", "wb");
		while (!feof($source)) {
			$content = fread($source, $this->partSize);
			fwrite($file, $content);
		}
		fclose($file);
		fclose($source);

		return rename($path . ".download", $path);
	}

	/**
	 * @param string $path
	 * @param int $folderId
	 * @param string|null $filename
	 *
	 * @return stdClass
	 * @throws Exception
	 */
	public function upload(string $path, int $folderId = 0, string $filename = null): stdClass
	{
		if (!file_exists($path) || !is_file($path) || !is_readable($path)) {
			throw new Exception("Invalid file");
		}

		$path = str_replace(array("\\", "_"), DIRECTORY_SEPARATOR, $path);
		$parts = explode(DIRECTORY_SEPARATOR, $path);

		if (!$filename) {
			$filename = end($parts);
		}

		$upload = $this->_createUpload();
		if (!is_object($upload)) {
			throw new Exception('File -> upload -> "createUpload" not returning the expected data!');
		}

		$params = array(
			"uploadid" => $upload->uploadid,
			"uploadoffset" => 0
		);

		$numFailures = 0;

		$file = fopen($path, "r");
		while (!feof($file)) {
			$content = fread($file, $this->partSize);
			do {
				try {
					$this->_write($content, $params);

					$params["uploadoffset"] += $this->partSize;

					$numFailures = 0;
					continue 2;

				} catch (Exception $e) {
					$numFailures++;
					sleep(3);
				}

			} while ($numFailures < 10);

			break;
		}

		if (@fclose($file)) {
			return $this->_save(intval($upload->uploadid), $filename, $folderId);
		}

		return new stdClass();
	}

	/**
	 * Delete file ( using file ID )
	 *
	 * @param int $fileId
	 *
	 * @return stdClass
	 * @throws Exception
	 * @noinspection PhpUnused
	 */
	public function delete(int $fileId): stdClass
	{
		$response = $this->request->get("deletefile", array("fileid" => $fileId));

		return property_exists($response, 'metadata') ? $response->metadata->isdeleted : $response;
	}

	/**
	 * Rename file ( using file ID )
	 *
	 * @param int $fileId
	 * @param string $name
	 *
	 * @return stdClass
	 * @throws Exception
	 * @noinspection PhpUnused
	 */
	public function rename(int $fileId, string $name): stdClass
	{
		if (empty($name)) {
			throw new Exception("Please, provide valid file name!");
		}

		$params = array(
			"fileid" => $fileId,
			"toname" => $name
		);

		return $this->request->get("renamefile", $params);
	}

	/**
	 * Moves file
	 *
	 * @param int $fileId
	 * @param int $folderId
	 *
	 * @return stdClass
	 * @throws Exception
	 * @noinspection PhpUnused
	 */
	public function move(int $fileId, int $folderId): stdClass
	{
		$params = array(
			"fileid" => $fileId,
			"tofolderid" => $folderId
		);

		return $this->request->get("renamefile", $params);
	}

	/**
	 * Copy file
	 *
	 * @param int $fileId
	 * @param int $folderId
	 *
	 * @return stdClass
	 * @throws Exception
	 * @noinspection PhpUnused
	 */
	public function copy(int $fileId, int $folderId): stdClass
	{
		$params = array(
			"fileid" => $fileId,
			"tofolderid" => $folderId
		);

		return $this->request->get("copyfile", $params);
	}

	/**
	 * Get file info ( using file ID )
	 *
	 * @param int $fileId
	 *
	 * @return stdClass
	 * @throws Exception
	 * @noinspection PhpUnused
	 */
	public function getInfo(int $fileId): stdClass
	{
		return $this->request->get("checksumfile", array("fileid" => $fileId));
	}

	/**
	 * Prepare to initiate Upload process
	 *
	 * @return stdClass
	 * @throws Exception
	 */
	private function _createUpload(): stdClass
	{
		return $this->request->get("upload_create");
	}

	/**
	 * @param int $uploadId
	 * @param string $name
	 * @param int $folderId
	 *
	 * @return stdClass
	 * @throws Exception
	 */
	private function _save(int $uploadId, string $name, int $folderId): stdClass
	{
		$params = array(
			"uploadid" => $uploadId,
			"name" => $name,
			"folderid" => $folderId
		);

		return $this->request->get("upload_save", $params);
	}

	/**
	 * Upload - write content chunk
	 *
	 * @param string $content
	 * @param array|null $params
	 *
	 * @return void
	 * @throws Exception
	 */
	private function _write(string $content, ?array $params): void
	{
		$this->request->put("upload_write", $content, $params);
	}
}