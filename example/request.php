<?php

require_once("../lib/pcloud/autoload.php");
pCloud\Config::$credentialPath = "../lib/pCloud/app.cred";

use pCloud\Request as Request;

try {
	$request = new Request();

	$response = $request->get("listfolder", ['folderid' => 0]);
} catch (Exception $e) {
	echo $e->getMessage();
}