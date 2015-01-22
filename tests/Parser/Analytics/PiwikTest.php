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

class PiwikTest extends AbstractParserTest {

    public function testOnParseResponse() {

        $client = $this->getClientWithResponse('<!DOCTYPE html><html><head></head><body>' .
'<script type="text/javascript">
<!--//--><![CDATA[//><!--
var _paq = _paq || [];(function(){var u=(("https:" == document.location.protocol) ? "" : "http://example.org/");_paq.push(["setSiteId", "1"]);_paq.push(["setTrackerUrl", u+"piwik.php"]);_paq.push(["setDoNotTrack", 1]);_paq.push(["trackPageView"]);_paq.push(["setIgnoreClasses", ["no-tracking","colorbox"]]);_paq.push(["enableLinkTracking"]);var d=document,g=d.createElement("script"),s=d.getElementsByTagName("script")[0];g.type="text/javascript";g.defer=true;g.async=true;g.src=u+"piwik.js";s.parentNode.insertBefore(g,s);})();
//--><!]]>
</script></body></html>');

        $ws = new \WebsiteInfo\WebsiteInfo($client, $this->dispatcher, array(
            new \WebsiteInfo\Parser\Analytics\Piwik()
        ));
        $result = $ws->get('http://example.org');

        $this->assertArrayHasKey('piwik_analytics', $result);
        $this->assertEquals('PiwikAnalytics', $result['piwik_analytics']['name']);
    }

    public function testPiwikAsCms() {
        $client = $this->getClientWithResponse('<!DOCTYPE html><html><head><script type="text/javascript">var translations = {}; if (typeof(piwik_translations) == \'undefined\') { var piwik_translations = new Object; }for(var i in translations) { piwik_translations[i] = translations[i];} </script></head><body></body></html>');
        $ws = new \WebsiteInfo\WebsiteInfo($client, $this->dispatcher, array(
            new \WebsiteInfo\Parser\Analytics\Piwik()
        ));
        $result = $ws->get('http://example.org');

        $this->assertArrayHasKey('cms', $result);
        $this->assertEquals('PiwikAnalytics', $result['cms']['name']);
    }
}
