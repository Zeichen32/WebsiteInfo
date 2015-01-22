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

use WebsiteInfo\Event\ParseResponseEvent;
use WebsiteInfo\Parser\AbstractParser;

class Joomla extends AbstractParser {

    public function onParseResponse(ParseResponseEvent $event)
    {
        $crawler = $event->getCrawler();

        $generator = $crawler->filterXPath('//head/meta[@name="generator"]')->extract(array('content'));

        if(count($generator) > 0) {

            $generator = array_shift($generator);

            if(false !== stripos($generator, 'joomla')) {
                $event->getData()->addSection('cms', array(
                    'name' => 'Joomla!',
                    'version' => 'unknown',
                    'score' => 1,
                    'raw' => $generator
                ));
            }
        }
    }
}
