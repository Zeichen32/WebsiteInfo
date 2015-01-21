<?php
/**
 * Created by Two Developers - Sven Motz und Jens Averkamp GbR
 * http://www.two-developers.com
 *
 * Developer: Jens Averkamp
 * Date: 20.01.2015
 * Time: 13:49
 */

namespace WebsiteInfo\Parser;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use WebsiteInfo\Event\ParseResponseEvent;

interface ParserInterface extends EventSubscriberInterface {

    /**
     * @param ParseResponseEvent $event
     * @return void
     */
    public function onParseResponse(ParseResponseEvent $event);
}
