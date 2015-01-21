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

class PHPBB extends AbstractParser {

    public function onParseResponse(ParseResponseEvent $event)
    {
        $html = (string) $event->getResponse()->getBody();

        if(false !== stripos($html, 'phpBB Group') || false !== stripos($html, 'Powered by phpBB')) {
            $event->getData()->addSection('cms', array(
                'name' => 'phpBB',
                'version' => 'unknown',
                'score' => 1,
                'raw' => null
            ));
        }
    }
}
