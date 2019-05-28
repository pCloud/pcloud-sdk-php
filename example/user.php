<?php

require_once('../vendor/autoload.php');
require_once("../lib/pCloud/autoload.php");

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();
$access_token = getenv('access_token');
$auth_params = array( 'access_token' => $access_token);
pCloud\Auth::setAuthParams($auth_params);

try {
	$pCloudUser = new pCloud\User();

	$info = $pCloudUser->getUserInfo();

	echo "Hello, ".$pCloudUser->getUserEmail();
} catch (Exception $e) {
	echo $e->getMessage();
}
