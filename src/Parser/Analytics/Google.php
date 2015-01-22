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

use GuzzleHttp\Url;
use WebsiteInfo\Event\ParseResponseEvent;
use WebsiteInfo\Parser\AbstractParser;

class Google extends AbstractParser {

    /**
     * @param ParseResponseEvent $event
     * @return void
     */
    public function onParseResponse(ParseResponseEvent $event)
    {
        $this->addAnalyticSection($event);
        $this->addFontsSection($event);
    }

    protected function addAnalyticSection(ParseResponseEvent $event) {
        $crawler = $event->getCrawler();

        $scriptTags = $crawler->filterXPath('//*/script');

        if($scriptTags->count() > 0) {
            /** @var \DOMElement $tag */
            foreach($scriptTags as $tag) {

                // Google Analytics inline script
                if(
                    false !== stripos($tag->textContent, 'google-analytics.com/ga.js') ||
                    false !== stripos($tag->textContent, 'google-analytics.com/analytics.js') ||
                    false !== stripos($tag->getAttribute('src'), 'google-analytics.com/urchin.js')
                ) {
                    if(!$event->getData()->hasSection('google_analytics')) {
                        $event->getData()->addSection('google_analytics', array(
                            'name' => 'GoogleAnalytics',
                            'score' => 1,
                        ));
                    }
                }

                // Google AdSense (old)
                if(false !== stripos($tag->textContent, 'GA_googleAddSlot') || false !== stripos($tag->textContent, 'GS_googleAddAdSenseService') || false !== stripos($tag->getAttribute('src'), '.googlesyndication.com')) {
                    if(!$event->getData()->hasSection('google_ads')) {
                        $event->getData()->addSection('google_ads', array(
                            'name' => 'GoogleAds',
                            'score' => 1,
                        ));
                    }
                }
            }
        }
    }

    protected function addFontsSection(ParseResponseEvent $event) {
        $crawler = $event->getCrawler();

        $linkTags = $crawler->filterXPath('//*/link')->extract(array('href'));
        if(count($linkTags) > 0) {
            $fonts = array();
            foreach($linkTags as $tag) {
                if(false !== stripos($tag, 'fonts.googleapis.com/css')) {
                    $url = Url::fromString(mb_strtolower($tag, 'UTF-8'));
                    $family = $url->getQuery()->get('family');

                    $test = explode(':', $family);
                    if(count($test) > 1) {
                        $fonts[] = array(
                            'family' => $test[0],
                            'weight' => explode(',', $test[1]),
                        );
                    }
                }
            }

            if(count($fonts) > 0) {
                $event->getData()->addSection('google_fonts', array(
                    'name' => 'GoogleFonts',
                    'fonts' => $fonts,
                ));
            }
        }
    }
}
