<!DOCTYPE html>
<html>
	<head>
	</head>
	<body>
	<?php
	$appInfoPath = "./app.info";
	$credentialPath = "../lib/pCloud/app.cred";

	require_once("../lib/pCloud/autoload.php");

	try {
		if (true) {

			$appInfo = pCloud\App::loadAppInfoFile($appInfoPath);

			$appInfo->code = $_GET["code"];
			if (!file_put_contents($appInfoPath, json_encode($appInfo, 128))) {
				throw new Exception("\"code\" not found");
			}

			$token = pCloud\App::getToken($appInfoPath, $credentialPath);
			echo "<p>Token: {$token}</p>";
		} else {
			echo "
				<form>
					<input type=\"text\" name=\"code\" placeholder=\"Code\"/>
					<input type=\"submit\">
				</form>";
		}
	} catch (Exception $e) {
		echo $e->getMessage();
	}
	?>
	</body>
</html>
