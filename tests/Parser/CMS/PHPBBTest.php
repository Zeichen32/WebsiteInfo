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

class PHPBBTest extends AbstractParserTest {

    public function testOnParseResponse() {

        $client = $this->getClientWithResponse('<!DOCTYPE html><html><head></head><body><div class="copyright">Powered by <a href="https://www.phpbb.com/">phpBB</a>® Forum Software © phpBB Group</div></body></html>');

        $ws = new \WebsiteInfo\WebsiteInfo($client, $this->dispatcher, array(
            new \WebsiteInfo\Parser\CMS\PHPBB()
        ));
        $result = $ws->get('http://example.org');

        $this->assertArrayHasKey('cms', $result);
        $this->assertEquals('phpBB', $result['cms']['name']);
        $this->assertEquals('unknown', $result['cms']['version']);
    }
}
