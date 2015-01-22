<?php
/**
 * Created by Two Developers - Sven Motz und Jens Averkamp GbR
 * http://www.two-developers.com
 *
 * Developer: Jens Averkamp
 * Date: 22.01.2015
 * Time: 11:11
 */

namespace WebsiteInfo\Parser\Analytics;

use WebsiteInfo\Event\ParseResponseEvent;
use WebsiteInfo\Parser\AbstractParser;

class Piwik extends AbstractParser {

    /**
     * @param ParseResponseEvent $event
     * @return void
     */
    public function onParseResponse(ParseResponseEvent $event)
    {
        $crawler = $event->getCrawler();

        $scriptTags = $crawler->filterXPath('//*/script');
        if($scriptTags->count() > 0) {
            /** @var \DOMElement $tag */
            foreach ($scriptTags as $tag) {
                if(false !== stripos($tag->textContent, 'piwik.js')) {
                    $event->getData()->addSection('piwik_analytics', array(
                        'name' => 'PiwikAnalytics',
                        'score' => 1,
                    ));
                }

                if(false !== stripos($tag->textContent, 'typeof(piwik_translations)')) {
                    $event->getData()->addSection('cms', array(
                        'name' => 'PiwikAnalytics',
                        'version' => 'unknown',
                        'score' => 1,
                    ));
                }
            }
        }
    }
}
