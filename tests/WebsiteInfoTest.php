<?php
/**
 * Created by Two Developers - Sven Motz und Jens Averkamp GbR
 * http://www.two-developers.com
 *
 * Developer: Jens Averkamp
 * Date: 21.01.2015
 * Time: 10:43
 */

require_once dirname(__DIR__) . '/vendor/autoload.php';

class WebsiteInfoTest extends AbstractParserTest {

    public function testGet() {

        $client = $this->getClientWithResponse('<!DOCTYPE html><html><head></head><body></body></html>');
        $ws = new \WebsiteInfo\WebsiteInfo($client, $this->dispatcher);
        $result = $ws->get('http://example.org');

        $this->assertArrayHasKey('headers', $result);
        $this->assertArrayHasKey('request', $result['headers']);
        $this->assertArrayHasKey('response', $result['headers']);
    }

    public function testGetWithDoctrineCache() {

        $url = 'http://example.org';
        $client = $this->getClientWithResponse('<!DOCTYPE html><html><head></head><body></body></html>');
        $cache = new \WebsiteInfo\Cache\DoctrineCache(new Doctrine\Common\Cache\ArrayCache());

        $ws = new \WebsiteInfo\WebsiteInfo($client, $this->dispatcher);
        $ws->setCache($cache);
        $ws->get('http://example.org');

        $this->assertTrue($cache->contains(\WebsiteInfo\WebsiteInfo::CACHE_PREFIX . md5($url)));
    }

    public function testGetWithArrayCache() {

        $url = 'http://example.org';
        $client = $this->getClientWithResponse('<!DOCTYPE html><html><head></head><body></body></html>');
        $cache = new \WebsiteInfo\Cache\ArrayCache();

        $ws = new \WebsiteInfo\WebsiteInfo($client, $this->dispatcher);
        $ws->setCache($cache);
        $ws->get('http://example.org');

        $this->assertTrue($cache->contains(\WebsiteInfo\WebsiteInfo::CACHE_PREFIX . md5($url)));
    }

    public function testGetWithZendCache() {
        $url = 'http://example.org';
        $client = $this->getClientWithResponse('<!DOCTYPE html><html><head></head><body></body></html>');

        $zendCache = new \Zend\Cache\Storage\Adapter\Memory();
        $zendCachePlugin = new \Zend\Cache\Storage\Plugin\ExceptionHandler();
        $zendCachePlugin->getOptions()->setThrowExceptions(false);
        $zendCache->addPlugin($zendCachePlugin);

        $cache = new \WebsiteInfo\Cache\ZendCache($zendCache);

        $ws = new \WebsiteInfo\WebsiteInfo($client, $this->dispatcher);
        $ws->setCache($cache);
        $ws->get('http://example.org');

        $this->assertTrue($cache->contains(\WebsiteInfo\WebsiteInfo::CACHE_PREFIX . md5($url)));
    }
}
