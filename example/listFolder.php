<?php

use pCloud\Sdk\Folder;

require_once("../lib/autoload.php");

try {

	$access_token = "ACCESS_TOKEN";
    $locationId = 1;

	$pCloudApp = new pCloud\Sdk\App();
	$pCloudApp->setAccessToken($access_token);
	$pCloudApp->setLocationId($locationId);

	$pCloudFolder = new pCloud\Sdk\Folder($pCloudApp);

    /**
     * @param int $folderId Folder ID.
     * @param pCloud\Sdk\Folder $pCloudFolder Folder object.
     */
	function appendFolder(int $folderId, Folder $pCloudFolder): void
    {
		echo '<ul style="list-style-type: none;">';

		$content = $pCloudFolder->getContent($folderId);

		foreach ($content as $item) {
			echo '<li>' . $item->name . '</li>';
			if ($item->isfolder) {
				appendFolder($item->folderid, $pCloudFolder);
			}
		}

		echo '</ul>';
	}

	appendFolder(0, $pCloudFolder);

} catch (Exception $e) {
	echo $e->getMessage();
}