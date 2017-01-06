<?

require_once("../lib/pcloud/autoload.php");

$path = "./app.info";

try {
	$appInfo = pCloud\App::loadAppInfoFile($path);
	$codeUrl = pCloud\App::getAuthorizeCodeUrl($appInfo);

	echo "Visit <a target=\"_blank\" href=\"{$codeUrl}\">{$codeUrl}</a>";
} catch (Exception $e) {
	echo $e->getMessage();
}
