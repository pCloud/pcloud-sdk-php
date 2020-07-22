<?php

require_once("../lib/pCloud/autoload.php");

try {
	$access_token = "ACCESS_TOKEN";
	$locationid = 1;

	$pCloudApp = new pCloud\App();
	$pCloudApp->setAccessToken($access_token);
	$pCloudApp->setLocationId($locationid);

	$pCloudUser = new pCloud\User($pCloudApp);

	$info = $pCloudUser->getUserInfo();

	echo "Hello, ".$pCloudUser->getUserEmail();
} catch (Exception $e) {
	echo $e->getMessage();
}