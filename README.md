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
* [Migration guide](#migration-guide)

---
<span id="system-requirements"></span>
# System requirements
  
  ### PHP 7.1.0 or higher is Required
  ~~~~
  Likelihood Of Impact: Critical
  ~~~~
  * The new minimum PHP version is now 7.1.0.
  * PHP [cURL extension](http://php.net/manual/en/curl.setup.php)

---

## Get started
<span id="get-started"></span>

### Register your application
<span id="register-your-application"></span>

In order to use this SDK, you have to register your application in [My applications](https://docs.pcloud.com).

---

## Install the SDK
<span id="install-the-sdk"></span>

### Using Composer
<span id="using-composer"></span>

Install [Composer](http://getcomposer.org/download/).

```bash
$ composer require pcloud/pcloud-php-sdk
```

or add the following to `composer.json` file

~~~~
"require": {
  "pcloud/pcloud-php-sdk": "3.*"
}
~~~~

### Manually
<span id="manually"></span>

Copy `lib/` folder and include `lib/autoload.php` in your code

---

## Initializing the SDK
<span id="initializing-the-sdk"></span>

The SDK uses an OAuth 2.0 access token to authorize requests to the pCloud API.
You can obtain a token using the SDK's authorization flow.
To allow the SDK to do that, find `App Key`, `App secret` and `Redirect URIs` in your application configuration page and add them to `/example/code.php` and `/example/auth.php`.

Note that `redirect_uri` is optional.

Run `/example/code.php` to get an authorization code and use this code in `/example/auth.php`. This will return `access_token` and `locationid`.

---

## Example
<span id="example"></span>

~~~~
// Include autoload.php and set the credential file path

$access_token = "ACCESS_TOKEN";
$locationid = 1;

$pCloudApp = new pCloud\Sdk\App();
$pCloudApp->setAccessToken($access_token);
$pCloudApp->setLocationId($locationid);

// Create Folder instance

$pcloudFolder = new pCloud\Sdk\Folder($pCloudApp);

// Create new folder in root

$folderId = $pcloudFolder->create("New folder");

// Create File instance

$pcloudFile = new pCloud\Sdk\File($pCloudApp);

// Upload new file in created folder

$fileMetadata = $pcloudFile->upload("./sample.png", $folderId);

// Get folder content

$folderContent = $pcloudFolder->getContent($folderId);
~~~~

### Creating custom requests

~~~~
$access_token = "";
$locationid = 1;

$pCloudApp = new pCloud\Sdk\App();
$pCloudApp->setAccessToken($access_token);
$pCloudApp->setLocationId($locationid);

$method = "userinfo";
$params = array();

$request = new pCloud\Sdk\Request($pCloudApp);
$response = $request->get($method, $params); // the second argument is optional
~~~~

## Migration guide
<span id="migration-guide"></span>

In version SDK version 3 the most significant changes are related to adjusting the namespaces.
To easily migrate to version 3 you need to change the following: 

~~~~
<?php 

namespace MyPhpApplication;

# this
use pCloud\App; // <-- Old versions

# change to: 
use pCloud\Sdk\App;  // <-- version 3.

-----

~~~~

The same change needs to be applied to all pCloud SDK names, pCloud\File, pCloud\Folder, ...

In case you intanciating the classes directly, you can make the following changes: 

~~~~
<?php 

namespace MyPhpApplication;

# this
$pCloudFolder = new pCloud\Folder($pCloudApp); // <-- Old versions

# change to: 
$pCloudFolder = new pCloud\Sdk\Folder($pCloudApp); // <-- version 3.

-----

~~~~

In case we missed something, you can find more examples in the "example" folder.
