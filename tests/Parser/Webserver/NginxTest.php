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

class NginxTest extends AbstractParserTest {

    public function testOnParseResponse() {

        $client = $this->getClientWithResponse('<!DOCTYPE html><html><head></head><body></body></html>', 200, array(
            'Server' => 'nginx/1.7.7'
        ));

        $ws = new \WebsiteInfo\WebsiteInfo($client, $this->dispatcher, array(
            new \WebsiteInfo\Parser\Webserver\Nginx()
        ));
        $result = $ws->get('http://example.org');

        $this->assertArrayHasKey('webserver', $result);
        $this->assertEquals('Nginx', $result['webserver']['name']);
        $this->assertEquals('1.7.7', $result['webserver']['version']);
    }
}
