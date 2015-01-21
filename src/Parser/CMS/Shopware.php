<?php
/**
 * Created by Two Developers - Sven Motz und Jens Averkamp GbR
 * http://www.two-developers.com
 *
 * Developer: Jens Averkamp
 * Date: 20.01.2015
 * Time: 14:54
 */

namespace WebsiteInfo\Parser\CMS;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Url;
use Symfony\Component\DomCrawler\Crawler;
use WebsiteInfo\Event\ParseResponseEvent;
use WebsiteInfo\Parser\AbstractParser;

class Shopware extends AbstractParser {

    public function onParseResponse(ParseResponseEvent $event)
    {
        $score = 0;
        $crawler = new Crawler((string) $event->getResponse()->getBody());

        $scriptTags = $crawler->filterXPath('//head/script')->extract(array('src'));
        if(count($scriptTags) > 0) {
            foreach($scriptTags as $tag) {
                if(false !== stripos($tag, '/Shopware/')) {
                    $score += 1;
                    break;
                }
            }
        }

        $stylesheets = $crawler->filterXPath('//head/link[@type="text/css"]')->extract(array('href'));
        if(count($stylesheets) > 0) {
            foreach($stylesheets as $tag) {
                if(false !== stripos($tag, '/Shopware/')) {
                    $score += 1;
                }

                //$score += $this->checkStylesheet($event, $tag);
            }
        }

        $shopwareFooter = $crawler->filterXPath('//*[@id="footer_wrapper"]/*/a');
        if(count($shopwareFooter) > 0) {
            $text = $shopwareFooter->text();
            if(false !== stripos($text, 'Shopware')) {
                $score += 5;
            }
        }

        if($score > 0) {
            $event->getData()->addSection('cms', array(
                'name' => 'Shopware',
                'version' => 'unknown',
                'score'=> $score,
                'raw' => null
            ));
        }
    }

    protected function checkStylesheet(ParseResponseEvent $event, $url) {

        try {
            $response = $event->getClient()->get($url);
        } catch(RequestException $exp) {
            return 0;
        }

        $body = (string) $response->getBody();

        if(false !== stripos($body, 'http://www.shopware.de')) {
            return 5;
        }

        return 0;
    }
}
