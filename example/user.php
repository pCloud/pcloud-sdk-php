<?php

require_once("../lib/autoload.php");

try {
	$access_token = "ACCESS_TOKEN";
	$locationid = 1;

	$pCloudApp = new pCloud\Sdk\App();
	$pCloudApp->setAccessToken($access_token);
	$pCloudApp->setLocationId($locationid);

	$pCloudUser = new pCloud\Sdk\User($pCloudApp);

	$info = $pCloudUser->getUserInfo();

	echo "Hello, ".$pCloudUser->getUserEmail();
} catch (Exception $e) {
	echo $e->getMessage();
}