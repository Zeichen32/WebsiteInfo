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


use Symfony\Component\DomCrawler\Crawler;
use WebsiteInfo\Event\ParseResponseEvent;
use WebsiteInfo\Parser\AbstractParser;

class VBulletin extends AbstractParser {

    public function onParseResponse(ParseResponseEvent $event)
    {
        $crawler = new Crawler((string) $event->getResponse()->getBody());

        $generator = $crawler->filterXPath('//head/meta[@name="generator"]')->extract(array('content'));

        if(count($generator) > 0) {

            $generator = array_shift($generator);

            if(false !== stripos($generator, 'vbulletin ')) {
                $event->getData()->addSection('cms', array(
                    'name' => 'vBulletin',
                    'version' => $this->parseVersion($generator),
                    'score' => 1,
                    'raw' => $generator
                ));
            }
        }
    }

    private function parseVersion($str) {
        if(preg_match('/\d+(\.\d+)+/', $str, $matches)) {
            return $matches[0];
        }

        return 'unknown';
    }

}
