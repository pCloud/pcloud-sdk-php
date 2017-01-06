<?php

require_once("../lib/pcloud/autoload.php");

try {
	$pCloudFile = new pCloud\File();

	$pCloudFile->upload("./sample.png");
} catch (Exception $e) {
	echo $e->getMessage();
}