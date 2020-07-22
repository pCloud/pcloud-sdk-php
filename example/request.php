<?php

require_once("../lib/pCloud/autoload.php");

use pCloud\Request as Request;

try {
	$access_token = "ACCESS_TOKEN";
	$locationid = 1;

	$pCloudApp = new pCloud\App();
	$pCloudApp->setAccessToken($access_token);
	$pCloudApp->setLocationId($locationid);

	$request = new Request($pCloudApp);

	$response = $request->get("listfolder", ['folderid' => 0]);
} catch (Exception $e) {
	echo $e->getMessage();
}