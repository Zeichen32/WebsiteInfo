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

class IISTest extends AbstractParserTest {

    public function testOnParseResponse() {

        $client = $this->getClientWithResponse('<!DOCTYPE html><html><head></head><body></body></html>', 200, array(
            'Server' => 'Microsoft-IIS/8.0'
        ));

        $ws = new \WebsiteInfo\WebsiteInfo($client, $this->dispatcher, array(
            new \WebsiteInfo\Parser\Webserver\IIS()
        ));
        $result = $ws->get('http://example.org');

        $this->assertArrayHasKey('webserver', $result);
        $this->assertEquals('IIS', $result['webserver']['name']);
        $this->assertEquals('8.0', $result['webserver']['version']);
    }
}
