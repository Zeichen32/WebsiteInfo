Website Info
============

[![Build Status](https://travis-ci.org/Zeichen32/WebsiteInfo.svg?branch=master)](https://travis-ci.org/Zeichen32/WebsiteInfo)

PHP library to retrieve server information like installed cms, webserver, dns lookup, etc... from any webpage

Requirements:

* PHP 5.4+
* Curl library installed
* [allow_url_fopen: On](http://php.net/manual/en/filesystem.configuration.php#ini.allow-url-fopen)

Install the library
-----
The preferred way to install this library is to use [Composer](http://getcomposer.org).

``` bash
$ php composer.phar require zeichen32/website-info ~1.0
```

Usage
-----

```php

// Create a new WebsiteInfo instance with all default parser
$ws = \WebsiteInfo\Factory::createWithDefaultParser();

// OR
$ws = \WebsiteInfo\Factory::create(array(
            new \WebsiteInfo\Parser\Webserver\Apache(),
            new \WebsiteInfo\Parser\Webserver\Nginx(),
            new \WebsiteInfo\Parser\Webserver\IIS(),
            // ...
    ));

// Retrieve informations about wordpress.com
$result = $ws->get('http://wordpress.com');

print_r($result);

```

```txt

Array
(
    [headers] => Array
        (
            [request] => Array
                (
                    [Host] => Array
                        (
                            [0] => wordpress.org
                        )

                    [user-agent] => Array
                        (
                            [0] => WebsiteInfo
                        )

                )

            [response] => Array
                (
                    [Server] => Array
                        (
                            [0] => nginx
                        )

                    [Content-Type] => Array
                        (
                            [0] => text/html; charset=utf-8
                        )

                )

        )

    [webserver] => Array
        (
            [name] => Nginx
            [version] => unknown
            [score] => 1
            [raw] => nginx
        )

    [embed] => Array
        (
            [title] => WordPress Blog Tool, Publishing Platform, and CMS
            [description] =>
            [url] => https://wordpress.org/
            [type] => link
            [embed_code] =>
            [images] => Array
                (
                    [collection] => Array
                        (
                            [0] => http://wpdotorg.files.wordpress.com/2012/10/red-negative-w-crop.jpg
                        )

                    [base] => Array
                        (
                            [image] => http://wpdotorg.files.wordpress.com/2012/10/red-negative-w-crop.jpg
                            [width] => 264
                            [height] => 354
                            [aspect_ration] =>
                        )

                )

            [author] => Array
                (
                    [name] =>
                    [url] =>
                )

            [provider] => Array
                (
                    [name] => wordpress
                    [url] => https://wordpress.org
                    [icon] => https://s.w.org/favicon.ico?2
                    [icons] => Array
                        (
                            [0] => https://wordpress.org/favicon.ico
                            [1] => https://wordpress.org/favicon.png
                        )

                )

        )

    [lookup] => Array
        (
            [ip] => Array
                (
                    [0] => 66.155.40.249
                    [1] => 66.155.40.250
                )

            [hostname] => wordpress.org
            [dns] => Array
                (
                    ...
                )

        )

)
```

Available parser
-----

Parser | Class | Description
------ | ----- | -----------
Apache | [WebsiteInfo\Parser\Webserver\Apache](https://github.com/Zeichen32/WebsiteInfo/blob/master/src/Parser/Webserver/Apache.php) | Try to find information about apache webserver
Nginx | [WebsiteInfo\Parser\Webserver\Nginx](https://github.com/Zeichen32/WebsiteInfo/blob/master/src/Parser/Webserver/Nginx.php) | Try to find information about nginx webserver
IIS | [WebsiteInfo\Parser\Webserver\IIS](https://github.com/Zeichen32/WebsiteInfo/blob/master/src/Parser/Webserver/IIS.php) | Try to find information about Microsoft IIS webserver
Drupal | [WebsiteInfo\Parser\CMS\Drupal](https://github.com/Zeichen32/WebsiteInfo/blob/master/src/Parser/CMS/Drupal.php) | Try to find information about installed Drupal CMS
Joomla | [WebsiteInfo\Parser\CMS\Joomla](https://github.com/Zeichen32/WebsiteInfo/blob/master/src/Parser/CMS/Joomla.php) | Try to find information about installed Joomla! CMS
Magento | [WebsiteInfo\Parser\CMS\Magento](https://github.com/Zeichen32/WebsiteInfo/blob/master/src/Parser/CMS/Magento.php) | Try to find information about installed Magento system
phpBB | [WebsiteInfo\Parser\CMS\PHPBB](https://github.com/Zeichen32/WebsiteInfo/blob/master/src/Parser/CMS/PHPBB.php) | Try to find information about installed phpBB system
Shopware | [WebsiteInfo\Parser\CMS\Shopware](https://github.com/Zeichen32/WebsiteInfo/blob/master/src/Parser/CMS/Shopware.php) | Try to find information about installed Shopware system
Typo3 | [WebsiteInfo\Parser\CMS\Typo3](https://github.com/Zeichen32/WebsiteInfo/blob/master/src/Parser/CMS/Typo3.php) | Try to find information about installed Typo3 CMS
vBulletin | [WebsiteInfo\Parser\CMS\VBulletin](https://github.com/Zeichen32/WebsiteInfo/blob/master/src/Parser/CMS/VBulletin.php) | Try to find information about installed vBulletin system
Wordpress | [WebsiteInfo\Parser\CMS\Wordpress](https://github.com/Zeichen32/WebsiteInfo/blob/master/src/Parser/CMS/Wordpress.php) | Try to find information about installed Wordpress CMS
Google | [WebsiteInfo\Parser\Analytics\Google](https://github.com/Zeichen32/WebsiteInfo/blob/master/src/Parser/Analytics/Google.php) | Try to find information about used google ads and analytics
Piwik | [WebsiteInfo\Parser\Analytics\Piwik](https://github.com/Zeichen32/WebsiteInfo/blob/master/src/Parser/Analytics/Piwik.php) | Try to find information about used piwik analytics
Embed | [WebsiteInfo\Parser\Embed\Embed](https://github.com/Zeichen32/WebsiteInfo/blob/master/src/Parser/Embed/Embed.php) | Try to find embed information
Lookup | [WebsiteInfo\Parser\Lookup](https://github.com/Zeichen32/WebsiteInfo/blob/master/src/Parser/Lookup.php) | Try to find lookup informations like dns, ip etc.

Create your own parser
-----

1) Create a new parser that do something with the response

```php

namespace Acme\Parser;

use WebsiteInfo\Event\ParseResponseEvent;
use WebsiteInfo\Parser\AbstractParser;

class MyParser extends AbstractParser {

    public function onParseResponse(ParseResponseEvent $event)
    {
        // Get response object
        $response = $event->getResponse();
        
        // Do something with the response
        $something = $this->doSomething( (string) $response->getBody() );

        // Add a new section to the output container
        $event->getData()->addSection('my_new_section', array(
            'foo' => 'bar',
            'version' => '1.0',
            'score' => 1,
            'raw' => $something
        ));
    }
}

```

2) Use your parser

```php

// Create a new WebsiteInfo instance
$ws = \WebsiteInfo\Factory::createWithDefaultParser();

// Register your parser
$ws->addParser(new \Acme\Parser\MyParser());

// Retrieve informations about wordpress.com
$result = $ws->get('http://wordpress.com');

```

Using the result container cache
-----

1) Using the ArrayCache (Memory Cache)

```php

// Create a new WebsiteInfo instance
$ws = \WebsiteInfo\Factory::createWithDefaultParser();

// Using the array cache
$ws->setCache(new \TwoDevs\Cache\ArrayCache());

// Retrieve informations about wordpress.com
$result = $ws->get('http://wordpress.com');

```

2) If doctrine cache is installed, it can be used to cache the result container using the doctrine cache adapter.

```php

// Create a new WebsiteInfo instance
$ws = \WebsiteInfo\Factory::createWithDefaultParser();

// Create a new DoctrineCache instance
$doctrineCache = new \Doctrine\Common\Cache\FilesystemCache('var/cache');

// Create a new DoctrineCache adapter
$cacheAdapter = new \TwoDevs\Cache\DoctrineCache($doctrineCache);

// Using the cache
$ws->setCache($cacheAdapter);

// Retrieve informations about wordpress.com
$result = $ws->get('http://wordpress.com');

```

3) If zend cache is installed, it can be used to cache the result container using the zend cache adapter.

```php

// Create a new WebsiteInfo instance
$ws = \WebsiteInfo\Factory::createWithDefaultParser();

// Create a new ZendStorage instance
$zendCache = new \Zend\Cache\Storage\Adapter\Memory();

// Create a new ZendCache adapter
$cacheAdapter = new \TwoDevs\Cache\ZendCache($zendCache);

// Using the cache
$ws->setCache($cacheAdapter);

// Retrieve informations about wordpress.com
$result = $ws->get('http://wordpress.com');

```

How to use a different HttpClient
-----

This library use the [Saxulum HttpClientInterface](https://github.com/saxulum/saxulum-http-client-interface) which 
allows you to simple change the used HttpClient. 

For example you want to use [Buzz](https://github.com/kriswallsmith/Buzz) as HttpClient:

1) Add the Buzz adapter to your composer.json:

``` bash
$ php composer.phar require saxulum-http-client-adapter-buzz ~1.0
```

2) Create a new BuzzClient

```php

    // Create a new Buzz Client 
    $buzz = new \Buzz\Browser();
    
    // Create the client adapter
    $client = new \Saxulum\HttpClient\Buzz\HttpClient($guzzle);
    
    // Create a new WebsiteInfo instance with all default parser and custom client
    $ws = \WebsiteInfo\Factory::createWithDefaultParser($client);
    
    // Retrieve informations about wordpress.com
    $result = $ws->get('http://wordpress.com');
    
    print_r($result);
        
```
