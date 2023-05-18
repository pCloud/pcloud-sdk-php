<?php

namespace pCloud\Sdk;

function autoload($name): void
{
	$name = str_replace(array("\\", "_"), "/", $name);
	$parts = explode("/", $name);
	$path = __DIR__ . DIRECTORY_SEPARATOR . end($parts) . ".php";
	if (is_file($path)) {
		require_once $path;
	}
}

spl_autoload_register("pCloud\Sdk\autoload");