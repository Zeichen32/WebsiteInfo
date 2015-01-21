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

class Magento extends AbstractParser {

    public function onParseResponse(ParseResponseEvent $event)
    {
        $crawler = new Crawler((string) $event->getResponse()->getBody());

        $scriptTags = $crawler->filterXPath('//head/script')->extract(array('src'));

        if(count($scriptTags) > 0) {

            foreach($scriptTags as $tag) {

                if(false !== stripos($tag, '/js/varien/js.js') || false !== stripos($tag, '/js/mage/translate.js')) {

                    $score = $this->tryToFindBackend($event);

                    $event->getData()->addSection('cms', array(
                        'name' => 'Magento',
                        'version' => 'unknown',
                        'score'=> $score,
                        'raw' => null
                    ));
                    break;
                }
            }
        }
    }

    protected function tryToFindBackend(ParseResponseEvent $event) {

        $client = $event->getClient();

        $url = Url::fromString($event->getRequest()->getUrl());
        $url->setPath('/admin');

        try {
            $response = $client->get((string) $url);
        } catch(RequestException $exp) {
            return 1;
        }

        $crawler = new Crawler((string) $response->getBody());

        $titleTag = $crawler->filterXPath('//head/title');

        $score = 0;
        if($titleTag->count() > 0) {
            $titleTag = $titleTag->text();
            if(false !== stripos($titleTag, 'magento')) {
                $score += 5;
            }
        }

        $titleTag = $crawler->filterXPath('//*[@id="loginForm"]/p');
        if($titleTag->count() > 0) {
            $titleTag = $titleTag->text();
            if(false !== stripos($titleTag, 'magento')) {
                $score += 5;
            }
        }

        return $score > 0 ? $score : 1;
    }
}
