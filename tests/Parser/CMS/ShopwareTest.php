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

class ShopwareTest extends AbstractParserTest {

    public function testOnParseResponse() {

        $client = $this->getClientWithResponse('<!DOCTYPE html><html><head><script type="text/javascript" src="/engine/Shopware/Plugins/Commercial/Frontend/SwagDemoshop/Views/scripts/js/bottom.js"></script><link type="text/css" media="all" rel="stylesheet" href="/engine/Shopware/Plugins/Commercial/Frontend/SwagDemoshop/Views/scripts/css/bottom.css" /></head><body><div class="shopware_footer">Realisiert mit  <a href="http://www.shopware.de" target="_blank" title="Shopware">Shopware</a></div></body></html>');

        $ws = new \WebsiteInfo\WebsiteInfo($client, $this->dispatcher, array(
            new \WebsiteInfo\Parser\CMS\Shopware()
        ));
        $result = $ws->get('http://example.org');

        $this->assertArrayHasKey('cms', $result);
        $this->assertEquals('Shopware', $result['cms']['name']);
        $this->assertEquals('unknown', $result['cms']['version']);
    }
}
