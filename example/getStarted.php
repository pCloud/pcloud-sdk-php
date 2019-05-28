<?php

// Include autoload.php
require_once('../vendor/autoload.php');
require_once("../lib/pCloud/autoload.php");

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

try {
        $access_token = getenv('access_token');
        $auth_params = array( 'access_token' => $access_token);
        pCloud\Auth::setAuthParams($auth_params);

	// Create Folder instance
	$pcloudFolder = new pCloud\Folder();

	// Create new folder in root
	$folderId = $pcloudFolder->create("New Folder");

	// Create File instance
	$pcloudFile = new pCloud\File();

	// Upload new file in created folder
	$fileMetadata = $pcloudFile->upload("./sample.png", $folderId);

	// Get folder content
	$folderContent = $pcloudFolder->getContent($folderId);
} catch (Exception $e) {
	echo $e->getMessage();
}
