<?php

require_once("../lib/autoload.php");

try {

	$appKey = "APP_KEY";
	$appSecret = "APP_SECRET";
	$redirect_uri = "REDIRECT_URI";

	$app = new pCloud\Sdk\App();
	$app->setAppKey($appKey);
	$app->setAppSecret($appSecret);
	$app->setRedirectURI($redirect_uri);

	$codeUrl = $app->getAuthorizeCodeUrl();

	echo 'Visit <a target="_blank" href="' . $codeUrl . '">' . $codeUrl . '</a>';

} catch (Exception $e) {
	echo $e->getMessage();
}
