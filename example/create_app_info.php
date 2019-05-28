<?php

require_once("../lib/pCloud/autoload.php");

try {
        pCloud\App::CreateAppInfoFile('./');
} catch (Exception $e) {
	echo $e->getMessage();
}
