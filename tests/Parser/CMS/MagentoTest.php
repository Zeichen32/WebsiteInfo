<?php
/**
 * Created by Two Developers - Sven Motz und Jens Averkamp GbR
 * http://www.two-developers.com
 *
 * Developer: Jens Averkamp
 * Date: 21.01.2015
 * Time: 11:18
 */

use GuzzleHttp\Client;

require_once dirname(__DIR__) . '/../../vendor/autoload.php';

class MagentoTest extends AbstractParserTest {

    public function testOnParseResponse() {

        $client = $this->createClient();

        $ws = new \WebsiteInfo\WebsiteInfo($client, $this->dispatcher, array(
            new \WebsiteInfo\Parser\CMS\Magento()
        ));
        $result = $ws->get('http://example.org');

        $this->assertArrayHasKey('cms', $result);
        $this->assertEquals('Magento', $result['cms']['name']);
        $this->assertEquals('unknown', $result['cms']['version']);
    }

    private function createClient() {

        $this->client = new Client();
        $body = \GuzzleHttp\Stream\Stream::factory('<!DOCTYPE html><html><head><script type="text/javascript" src="http://www.example.org/js/varien/js.js"></script><script type="text/javascript" src="http://www.example.org/js/mage/translate.js"></script></head><body></body></html>');
        $bodyAdmin = \GuzzleHttp\Stream\Stream::factory('<!DOCTYPE html><html><head><title>Log into Magento Admin Page</title><script type="text/javascript" src="http://www.example.org/js/varien/js.js"></script><script type="text/javascript" src="http://www.example.org/js/mage/translate.js"></script></head><body><form method="post" action="" id="loginForm" autocomplete="off"><p class="legal">Magento is a trademark of Magento Inc. Copyright &copy; 2015 Magento Inc.</p></form></body></html>');

        $mock = new \GuzzleHttp\Subscriber\Mock(array(
            new \GuzzleHttp\Message\Response(200, array(), $body),
            new \GuzzleHttp\Message\Response(200, array(), $bodyAdmin)
        ));

        $this->client->getEmitter()->attach($mock);

        return $this->client;
    }
}
