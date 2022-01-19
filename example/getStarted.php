<?php

/**
 * If you are hitting ofter timeouts, or you plan to make heavy uploads,
 * you can try to adjust the php default socket execution timeout ( if you are allowed to ).
 * In addition, you can also call: $pCloudApp->setCurlExecutionTimeout($newTimeout) to also allow the cURL
 * function to work with higher timeouts.
 */
ini_set("default_socket_timeout", 300);


// Include autoload.php and set the credential file path

require_once("../lib/autoload.php");

try {
	$access_token = "ACCESS_TOKEN";
	$locationid = 1;

	$pCloudApp = new pCloud\Sdk\App();
	$pCloudApp->setAccessToken($access_token);
	$pCloudApp->setLocationId($locationid);
	$pCloudApp->setCurlExecutionTimeout(10);

	// Create Folder instance
	$pcloudFolder = new pCloud\Sdk\Folder($pCloudApp);

	// Create new folder in root
	$folderId = $pcloudFolder->create("New folder");

	// Create File instance
	$pcloudFile = new pCloud\Sdk\File($pCloudApp);

	// Upload new file in created folder
	$fileMetadata = $pcloudFile->upload("./sample.png", $folderId);

	// Get folder content
	$folderContent = $pcloudFolder->getContent($folderId);

} catch (Exception $e) {
	echo $e->getMessage();
}
