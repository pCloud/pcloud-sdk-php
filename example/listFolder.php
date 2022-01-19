<?php

require_once("../lib/autoload.php");

try {

	$access_token = "ACCESS_TOKEN";
	$locationid = 1;

	$pCloudApp = new pCloud\Sdk\App();
	$pCloudApp->setAccessToken($access_token);
	$pCloudApp->setLocationId($locationid);

	$pCloudFolder = new pCloud\Sdk\Folder($pCloudApp);

	function appendFolder($folderid, $pCloudFolder) {

		echo '<ul style="list-style-type: none;">';

		$content = $pCloudFolder->getContent($folderid);

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