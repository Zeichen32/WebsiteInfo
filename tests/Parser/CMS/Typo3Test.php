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

class Typo3Test extends AbstractParserTest {

    public function testOnParseResponse() {

        $client = $this->getClientWithResponse('<!DOCTYPE html><html><head><meta name="generator" content="TYPO3 6.1 CMS"></head><body></body></html>');

        $ws = new \WebsiteInfo\WebsiteInfo($client, $this->dispatcher, array(
            new \WebsiteInfo\Parser\CMS\Typo3()
        ));
        $result = $ws->get('http://example.org');

        $this->assertArrayHasKey('cms', $result);
        $this->assertEquals('Typo3', $result['cms']['name']);
        $this->assertEquals('6.1', $result['cms']['version']);
    }
}
