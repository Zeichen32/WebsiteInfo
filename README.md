Website Info
============

PHP library to retrieve server information like installed cms, webserver, dns lookup, etc... from any webpage

Requirements:

* PHP 5.3+
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

Using result container cache
-----

If doctrine cache is installed, it can be used to cache the result container.

```php

// Create a new WebsiteInfo instance
$ws = \WebsiteInfo\Factory::createWithDefaultParser();

// Create a new Cache instance
$cache = new \Doctrine\Common\Cache\FilesystemCache('var/cache');

// Using the cache
$ws->setCache($cache);

// Retrieve informations about wordpress.com
$result = $ws->get('http://wordpress.com');

```

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
