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
}
