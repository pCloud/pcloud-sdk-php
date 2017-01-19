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

The SDK uses an OAuth 2.0 access token to authorize requests to the pCloud API.
You can obtain a token using the SDK's authorization flow.
To allow the SDK to do that, find `App Key`, `App secret` and `Redirect URIs` in your application configuration page and add them to `/example/app.info` file.

~~~~
{
  "appKey": "App key",
  "appSecret": "App secret",
  "redirect_uri": "Redirect URI"
}
~~~~

Note that `redirect_uri` is optional.

Run `/example/code.php` to get an authorization code and use this code in `/example/auth.php`. This will generate `/lib/pCloud/app.cred` file with your credentials.

---

## Example

~~~~
// Include autoload.php and set the credential file path

require_once("../lib/pcloud/autoload.php");
pCloud\Config::$credentialPath = "../lib/pCloud/app.cred";

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