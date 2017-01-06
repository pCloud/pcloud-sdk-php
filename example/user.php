<?php

require_once("../lib/pcloud/autoload.php");

try {
	$pCloudUser = new pCloud\User();

	$info = $pCloudUser->getUserInfo();

	echo "Hello, ".$pCloudUser->getUserEmail();
} catch (Exception $e) {
	echo $e->getMessage();
}