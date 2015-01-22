<?php
/**
 * Created by Two Developers - Sven Motz und Jens Averkamp GbR
 * http://www.two-developers.com
 *
 * Developer: Jens Averkamp
 * Date: 21.01.2015
 * Time: 12:12
 */

namespace WebsiteInfo;

use Saxulum\HttpClient\Guzzle\HttpClient as Client;
use Saxulum\HttpClient\HttpClientInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use TwoDevs\Cache\CacheInterface;

class Factory {

    /**
     * Create a new WebsiteInfo instance
     *
     * @param array $parser
     * @param HttpClientInterface $client
     * @param CacheInterface $cache
     * @return WebsiteInfo
     */
    public static function create(array $parser = array(), HttpClientInterface $client = null, CacheInterface $cache = null) {

        if(null === $client) {
            $guzzle = new \GuzzleHttp\Client(['defaults' => ['verify' => false]]);
            $client = new Client($guzzle);
        }

        $dispatcher = new EventDispatcher();
        $ws = new WebsiteInfo($client, $dispatcher, $parser);

        if(null !== $cache) {
            $ws->setCache($cache);
        }

        return $ws;
    }

    /**
     * Create a new WebsiteInfo instance with default parser
     *
     * @param HttpClientInterface $client
     * @param CacheInterface $cache
     * @return WebsiteInfo
     */
    public static function createWithDefaultParser(HttpClientInterface $client = null, CacheInterface $cache = null) {

        $parser = array(
            new Parser\Lookup(),
            new Parser\Webserver\Apache(),
            new Parser\Webserver\Nginx(),
            new Parser\Webserver\IIS(),
            new Parser\CMS\Typo3(),
            new Parser\CMS\Drupal(),
            new Parser\CMS\Wordpress(),
            new Parser\CMS\Joomla(),
            new Parser\CMS\VBulletin(),
            new Parser\CMS\PHPBB(),
            new Parser\CMS\Magento(),
            new Parser\CMS\Shopware(),
            new Parser\Embed\Embed(),
            new Parser\Analytics\Google(),
            new Parser\Analytics\Piwik(),
        );

        return self::create($parser, $client, $cache);
    }
}
