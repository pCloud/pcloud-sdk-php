<?php

/**
 * If you are hitting often timeouts, or you plan to make heavy uploads,
 * you can try to adjust the php default socket execution timeout.
 * In addition, you can also call: $pCloudApp->setCurlExecutionTimeout($newTimeout) to also allow the cURL
 * function to work with higher timeouts.
 */
ini_set("default_socket_timeout", 300);


// Include autoload.php and set the credential file path

require_once("../lib/autoload.php");

try {
    $access_token = "ACCESS_TOKEN";
    $locationId = 1;

	$pCloudApp = new pCloud\Sdk\App();
	$pCloudApp->setAccessToken($access_token);
	$pCloudApp->setLocationId($locationId);
	$pCloudApp->setCurlExecutionTimeout(10);

	// Create Folder instance
	$pCloudFolder = new pCloud\Sdk\Folder($pCloudApp);

	// Create new folder in root
	$folderId = $pCloudFolder->create("New folder");

	// Create File instance
	$pCloudFile = new pCloud\Sdk\File($pCloudApp);

	// Upload new file in created folder
	$fileMetadata = $pCloudFile->upload("./sample.png", $folderId);

	// Get folder content
	$folderContent = $pCloudFolder->getContent($folderId);

    if (isset($folderContent[0]) && property_exists($folderContent[0], 'name')) {
        print 'All good, uploaded image:' . $folderContent[0]->name . ' into folder id: ' . $folderContent[0]->parentfolderid;
    } else {
        print 'Something went wrong, please check the response: ';
        print_r($folderContent);
    }

} catch (Exception $e) {
	echo $e->getMessage();
}
