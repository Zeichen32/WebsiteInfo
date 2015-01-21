<?php
/**
 * Created by Two Developers - Sven Motz und Jens Averkamp GbR
 * http://www.two-developers.com
 *
 * Developer: Jens Averkamp
 * Date: 21.01.2015
 * Time: 11:18
 */

require_once dirname(__DIR__) . '/../../vendor/autoload.php';

class WordpressTest extends AbstractParserTest {

    public function testOnParseResponse() {

        $client = $this->getClientWithResponse('<!DOCTYPE html><html><head><meta name="generator" content="WordPress 4.1"></head><body></body></html>');

        $ws = new \WebsiteInfo\WebsiteInfo($client, $this->dispatcher, array(
            new \WebsiteInfo\Parser\CMS\Wordpress()
        ));
        $result = $ws->get('http://example.org');

        $this->assertArrayHasKey('cms', $result);
        $this->assertEquals('WordPress', $result['cms']['name']);
        $this->assertEquals('4.1', $result['cms']['version']);
    }
}
