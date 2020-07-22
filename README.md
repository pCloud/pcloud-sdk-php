# pCloud SDK for PHP

A PHP library to access [pCloud API](https://docs.pcloud.com/)

---

## Table of Contents
* [System requirements](#system-requirements)
* [Get started](#get-started)
  * [Register your application](#register-your-application)
* [Install the SDK](#install-the-sdk)
  * [Using Composer](#using-composer)
  * [Manually](#manually)
* [Initializing the SDK](#initializing-the-sdk)
* [Example](#example)

---

## System requirements

  * PHP 5.6+
  * PHP [cURL extension](http://php.net/manual/en/curl.setup.php)

---

## Get started

### Register your application

In order to use this SDK, you have to register your application in [My applications](https://docs.pcloud.com).

---

## Install the SDK

### Using Composer

Install [Composer](http://getcomposer.org/download/).

```bash
$ composer require pcloud/pcloud-php-sdk
```

or add the following to `composer.json` file

~~~~
"require": {
  "pcloud/pcloud-php-sdk": "2.*"
}
~~~~

### Manually

Copy `lib/` folder and include `lib/pCloud/autoload.php` in your code

---

## Initializing the SDK

The SDK uses an OAuth 2.0 access token to authorize requests to the pCloud API.
You can obtain a token using the SDK's authorization flow.
To allow the SDK to do that, find `App Key`, `App secret` and `Redirect URIs` in your application configuration page and add them to `/example/code.php` and `/example/auth.php`.

Note that `redirect_uri` is optional.

Run `/example/code.php` to get an authorization code and use this code in `/example/auth.php`. This will return `access_token` and `locationid`.

---

## Example

~~~~
// Include autoload.php and set the credential file path

$access_token = "ACCESS_TOKEN";
$locationid = 1;

$pCloudApp = new pCloud\App();
$pCloudApp->setAccessToken($access_token);
$pCloudApp->setLocationId($locationid);

// Create Folder instance

$pcloudFolder = new pCloud\Folder($pCloudApp);

// Create new folder in root

$folderId = $pcloudFolder->create("New folder");

// Create File instance

$pcloudFile = new pCloud\File($pCloudApp);

// Upload new file in created folder

$fileMetadata = $pcloudFile->upload("./sample.png", $folderId);

// Get folder content

$folderContent = $pcloudFolder->getContent($folderId);
~~~~

### Creating custom requests

~~~~
$access_token = "";
$locationid = 1;

$pCloudApp = new pCloud\App();
$pCloudApp->setAccessToken($access_token);
$pCloudApp->setLocationId($locationid);

$method = "userinfo";
$params = array();

$request = new pCloud\Request($pCloudApp);
$response = $request->get($method, $params); // the second argument is optional
~~~~
