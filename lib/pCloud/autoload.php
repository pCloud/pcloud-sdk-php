<?php

namespace pCloud;

function autoload($name) {
	$name = str_replace(array("\\", "_"), "/", $name);
	$parts = explode("/", $name);
	$path = __DIR__.DIRECTORY_SEPARATOR.end($parts).".php";
	if (is_file($path)) {
		require_once $path;
	}
}

spl_autoload_register("pCloud\autoload");