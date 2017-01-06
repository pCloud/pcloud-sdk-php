# pCloud SDK for PHP 5.6+

[![Build Status](https://travis-ci.org/ivankrastev26/pcloud-php-sdk.svg?branch=master)](https://travis-ci.org/ivankrastev26/pcloud-php-sdk)

A PHP library to access [pCloud Documentation](https://docs.pcloud.com/)

Requirements:

  * PHP 5.6+
  * PHP [cURL extension](http://php.net/manual/en/curl.setup.php)

## Setup

If you are using [Composer](http://getcomposer.org/download/), create `composer.json` file and add this code:
~~~~
{
	"require": {
		"pcloud/pcloud-php-sdk": "1.*"
	}
}
~~~~
If you are not using Composer, copy `lib/` folder and include `lib/pCloud/autoload.php` in your code

## Using pCloud API

Log in your pCloud account and go to [My applications](https://docs.pcloud.com/oauth/index.html)

Register a new app, go to `Settings`

Enter your `App Key` and `App secret` in `/example/app.info` file

~~~~
{
	"appKey": "App key",
	"appSecret": "App secret",
	"redirect_uri": ""
}
~~~~

### Running the examples

Run `code.php` to get an authorization code and use this code in `auth.php`