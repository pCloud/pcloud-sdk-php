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

Install [Composer](http://getcomposer.org/download/). Add the following to `composer.json` file

~~~~
"require": {
  "pcloud/pcloud-php-sdk": "1.*"
}
~~~~

### Manually

Copy `lib/` folder and include `lib/pCloud/autoload.php` in your code

---

## Initializing the SDK

The SDK has several ways for authentication.
The first way is OAuth 2.0 access token to authorize requests to the pCloud API.
You can obtain a token using the SDK's authorization flow.
To allow the SDK to do that, find `App Key`, `App secret` and `Redirect URIs`.
Either create a file called app.info in the examples directory or run `create_app_info.php`

~~~~
{
  "appKey": "App key",
  "appSecret": "App secret",
  "redirect_uri": "Redirect URI"
}
~~~~

Note that `redirect_uri` is optional.

Run `/example/code.php` to get an authorization code and use this code in `/example/auth.php`.
`/example/auth/php` will return the access_token. You should pass it as an associative array to
`Auth::setAuthParams();` (example below)

Alternatively, you can use any of the authentication methods here:
https://docs.pcloud.com/methods/intro/authentication.html
You need to pass an associative array with the same names as are described in the link above.
E.g. for username and password, you should pass
~~~~
( "username"=>"myusername", "password"=>"secret" )
~~~~

---

## Example

~~~~
// Include autoload.php
require_once('../vendor/autoload.php');
require_once("../lib/pcloud/autoload.php");

// Load Config
$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

// Set the credentials, this should be done only once
$auth_params = array( "access_token" => "my_secret_token");
pCloud\Auth::setAuthParams($auth_params);

// Create Folder instance
$pcloudFolder = new pCloud\Folder();

// Create new folder in root
$folderId = $pcloudFolder->create("New folder");

// Create File instance
$pcloudFile = new pCloud\File();

// Upload new file in created folder
$fileMetadata = $pcloudFile->upload("./sample.png", $folderId);

// Get folder content
$folderContent = $pcloudFolder->getContent($folderId);
~~~~

### Creating custom requests

~~~~
$method = "userinfo";
$params = array();

$request = new pCloud\Request();
$response = $request->get($method, $params); // the second argument is optional
~~~~
