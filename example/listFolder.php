<?php

require_once('../vendor/autoload.php');
require_once("../lib/pcloud/autoload.php");

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();
$access_token = getenv('access_token');
$auth_params = array( 'access_token' => $access_token);
pCloud\Auth::setAuthParams($auth_params);

try {
	$pCloudFolder = new pCloud\Folder();

	function appendFolder($folderid, $pCloudFolder) {
		echo "<ul style=\"list-style-type: none;\">";
		$content = $pCloudFolder->getContent($folderid);

		foreach ($content as $item) {
			echo "<li>{$item->name}</li>";
			if ($item->isfolder) {
				appendFolder($item->folderid, $pCloudFolder);
			}
		}
		echo "</ul>";
	}

	appendFolder(0, $pCloudFolder);
} catch (Exception $e) {
	echo $e->getMessage();
}
