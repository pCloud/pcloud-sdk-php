<?php

require_once("../lib/autoload.php");

use pCloud\Sdk\Request as Request;

try {
    $access_token = "ACCESS_TOKEN";
    $locationId = 1;

	$pCloudApp = new pCloud\Sdk\App();
	$pCloudApp->setAccessToken($access_token);
	$pCloudApp->setLocationId($locationId);

	$request = new Request($pCloudApp);

	$response = $request->get("listfolder", ['folderid' => 0]);

    echo '<pre>';
    print_r($response);
    echo '</pre>';

} catch (Exception $e) {
	echo $e->getMessage();
}