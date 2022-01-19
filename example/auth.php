<!DOCTYPE html>
<html lang="en">
	<head><title>Auth page</title></head>
	<body>
	<?php

	require_once("../lib/autoload.php");

	try {
		if (isset($_GET["code"]) && isset($_GET["locationid"])) {
			$appKey="APP_KEY";
			$appSecret="APP_SECRET";
			$redirect_uri="REDIRECT_URI";

			$app = new pCloud\Sdk\App();
			$app->setAppKey($appKey);
			$app->setAppSecret($appSecret);
			$app->setRedirectURI($redirect_uri);

			$token = $app->getTokenFromCode($_GET["code"], $_GET['locationid']);

			echo "Token: " . $token["access_token"] . "</br>";
			echo "Locationid: " . $token["locationid"];

		} else {

			echo "
				<form>
					<input type=\"text\" name=\"code\" placeholder=\"Code\"/>
					<input type=\"text\" name=\"locationid\" placeholder=\"Locationid\"/>
					<button type=\"submit\">Submit</button>
				</form>";

		}

	} catch (Exception $e) {
		echo $e->getMessage();
	}
	?>
	</body>
</html>