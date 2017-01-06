<?php

namespace pCloud;

class Auth {

	public static function getAuth($credentialPath) {
		if (!file_exists($credentialPath)) {
			throw new Exception("Couldn't find credential file");			
		}

		$file = file_get_contents($credentialPath);
		$credential = json_decode($file, true);

		if (!isset($credential["access_token"]) || empty($credential["access_token"])) {
			throw new Exception("Couldn't find \"access_token\"");			
		}

		return $credential;
	}
}