<?php
/**
 * Created by Two Developers - Sven Motz und Jens Averkamp GbR
 * http://www.two-developers.com
 *
 * Developer: Jens Averkamp
 * Date: 22.01.2015
 * Time: 11:11
 */

namespace Parser\Analytics;

use WebsiteInfo\Event\ParseResponseEvent;
use WebsiteInfo\Parser\AbstractParser;

class GoogleAnalytics extends AbstractParser {
    /**
     * @param ParseResponseEvent $event
     * @return void
     */
    public function onParseResponse(ParseResponseEvent $event)
    {
        $crawler = $event->getCrawler();


    }

}
