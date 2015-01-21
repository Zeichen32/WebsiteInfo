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

use GuzzleHttp\Client;
use Symfony\Component\EventDispatcher\EventDispatcher;

class Factory {

    /**
     * Create a new WebsiteInfo instance
     *
     * @param array $parser
     * @param array $clientOptions
     * @return WebsiteInfo
     */
    public static function create(array $parser = array(), $clientOptions = ['defaults' => ['verify' => false]]) {

        $client = new Client($clientOptions);
        $dispatcher = new EventDispatcher();
        return new WebsiteInfo($client, $dispatcher, $parser);
    }

    /**
     * Create a new WebsiteInfo instance with default parser
     *
     * @param array $clientOptions
     * @return WebsiteInfo
     */
    public static function createWithDefaultParser($clientOptions = ['defaults' => ['verify' => false]]) {

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
        );

        return self::create($parser, $clientOptions);
    }
}
