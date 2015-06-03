# QuickAppsCMS

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/quickapps/cms/badges/quality-score.png?b=2.0)](https://scrutinizer-ci.com/g/quickapps/cms/?branch=2.0)
[![Build Status](https://travis-ci.org/quickapps/cms.svg?branch=2.0)](https://travis-ci.org/quickapps/cms)
[![Latest Stable Version](https://poser.pugx.org/quickapps/cms/v/stable)](https://packagist.org/packages/quickapps/cms)
[![Total Downloads](https://poser.pugx.org/quickapps/cms/downloads)](https://packagist.org/packages/quickapps/cms)
[![Latest Unstable Version](https://poser.pugx.org/quickapps/cms/v/unstable)](https://packagist.org/packages/quickapps/cms)
[![License](https://poser.pugx.org/quickapps/cms/license)](https://packagist.org/packages/quickapps/cms)

[![QuickAppsCMS](http://quickappscms.org/system/img/logo.png)](http://www.quickappscms.org)

Free open source content management system for PHP, released under GPL License
and powered by [CakePHP 3.0](http://cakephp.org) MVC framework.

**This is an unstable repository and should be treated as an alpha.**

## Requirements

* Apache with mod_rewrite
* PHP 5.4.19 or higher
* mbstring extension installed
* mcrypt extension installed
* intl extension installed
* fileinfo extension installed
* PHP safe mode disabled
* Supported database storage engines:
   * MySQL (5.1.10 or greater)
   * PostgreSQL
   * Microsoft SQL Server (2008 or higher)
   * SQLite 3
* Write permission in your server

## Installing QuickAppsCMS

You must install QuickAppsCMS using [composer](http://getcomposer.org).
QuickAppsCMS is designed to run as a stand alone application, so you must
use the [website skeleton](https://github.com/QuickAppsCMS/website) as
starting point:

1. Download [Composer](http://getcomposer.org/doc/00-intro.md) or update `composer self-update`.
2. Run `php composer.phar create-project -s dev quickapps/website [your_website_name]`.

If Composer is installed globally, run:

    composer create-project -s dev quickapps/website [website_name]

After composer is done visit `http://example.com/` and start QuickAppsCMS installation.

## Links of Interest

 * [Official Site](http://www.quickappscms.org)
 * [GitHub Repo](https://github.com/quickapps/cms)
 * [API 2.0](http://api.quickappscms.org/2.0)
 * [Issue Tracker](https://github.com/quickapps/cms/issues)
 * [Google Group](https://groups.google.com/group/quickapps-cms)