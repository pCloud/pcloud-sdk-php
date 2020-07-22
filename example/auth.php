<!DOCTYPE html>
<html>
	<head>
	</head>
	<body>
	<?php

	require_once("../lib/pCloud/autoload.php");

	try {
		if (isset($_GET["code"]) && isset($_GET["locationid"])) {
			$appKey="APP_KEY";
			$appSecret="APP_SECRET";
			$redirect_uri="REDIRECT_URI";

			$app = new pCloud\App();
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
					<input type=\"submit\">
				</form>";
		}
	} catch (Exception $e) {
		echo $e->getMessage();
	}
	?>
	</body>
</html>