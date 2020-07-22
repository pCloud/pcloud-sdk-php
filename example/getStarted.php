<?php

// Include autoload.php and set the credential file path

require_once("../lib/pCloud/autoload.php");

try {
	$access_token = "ACCESS_TOKEN";
	$locationid = 1;

	$pCloudApp = new pCloud\App();
	$pCloudApp->setAccessToken($access_token);
	$pCloudApp->setLocationId($locationid);

	// Create Folder instance

	$pcloudFolder = new pCloud\Folder($pCloudApp);

	// Create new folder in root

	$folderId = $pcloudFolder->create("New folder");

	// Create File instance

	$pcloudFile = new pCloud\File($pCloudApp);

	// Upload new file in created folder

	$fileMetadata = $pcloudFile->upload("./sample.png", $folderId);

	// Get folder content

	$folderContent = $pcloudFolder->getContent($folderId);
} catch (Exception $e) {
	echo $e->getMessage();
}
