<?php

// Include autoload.php and set the credential file path

require_once("../lib/pcloud/autoload.php");
pCloud\Config::$credentialPath = "../lib/pCloud/app.cred";


try {
	// Create Folder instance

	$pcloudFolder = new pCloud\Folder();

	// Create new folder in root

	$folderId = $pcloudFolder->create("New folder");

	// Create File instance

	$pcloudFile = new pCloud\File();

	// Upload new file in created folder

	$fileMetadata = $pcloudFile->upload("./sample.png", $folderId);

	// Get folder content

	$folderContent = $pcloudFolder->getContent($folderId);
} catch (Exception $e) {
	echo $e->getMessage();
}
