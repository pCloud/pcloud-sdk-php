<?php

require_once("../lib/autoload.php");

try {
	$access_token = "ci5XZzwtosbxgx3RZF5Dqo7ZipFkuTaUhh45JEBAfrOdzHfQC6Nk";
	$locationId = 2;

	$pCloudApp = new pCloud\Sdk\App();
	$pCloudApp->setAccessToken($access_token);
	$pCloudApp->setLocationId($locationId);

	$pCloudUser = new pCloud\Sdk\User($pCloudApp);

	$info = $pCloudUser->getUserInfo();

	echo 'Hello, ' . $pCloudUser->getUserEmail();

} catch (Exception $e) {
	echo $e->getMessage();
}