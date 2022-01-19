<?php

require_once("../lib/autoload.php");

use pCloud\Sdk\Request as Request;

try {
	$access_token = "ACCESS_TOKEN";
	$locationid = 1;

	$pCloudApp = new pCloud\Sdk\App();
	$pCloudApp->setAccessToken($access_token);
	$pCloudApp->setLocationId($locationid);

	$request = new Request($pCloudApp);

	$response = $request->get("listfolder", ['folderid' => 0]);
} catch (Exception $e) {
	echo $e->getMessage();
}