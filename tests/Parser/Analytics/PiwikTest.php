<?php
/**
 * Created by Two Developers - Sven Motz und Jens Averkamp GbR
 * http://www.two-developers.com
 *
 * Developer: Jens Averkamp
 * Date: 22.01.2015
 * Time: 12:16
 */

require_once dirname(__DIR__) . '/../../vendor/autoload.php';

class GoogleTest extends AbstractParserTest {

    public function testGoogleAnalytics() {

        $client = $this->getClientWithResponse('<!DOCTYPE html><html><head></head><body><script type="text/javascript"><!-- var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www."); document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E")); //--></script></body></html>');

        $ws = new \WebsiteInfo\WebsiteInfo($client, $this->dispatcher, array(
            new \WebsiteInfo\Parser\Analytics\Google()
        ));
        $result = $ws->get('http://example.org');

        $this->assertArrayHasKey('google_analytics', $result);
        $this->assertEquals('GoogleAnalytics', $result['google_analytics']['name']);
    }

    public function testGoogleAds() {

        $client = $this->getClientWithResponse('<!DOCTYPE html><html><head><script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"> </script></head><body></body></html>');

        $ws = new \WebsiteInfo\WebsiteInfo($client, $this->dispatcher, array(
            new \WebsiteInfo\Parser\Analytics\Google()
        ));
        $result = $ws->get('http://example.org');

        $this->assertArrayHasKey('google_ads', $result);
        $this->assertEquals('GoogleAds', $result['google_ads']['name']);
    }
}
