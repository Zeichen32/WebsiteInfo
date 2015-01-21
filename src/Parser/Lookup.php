<?php
/**
 * Created by Two Developers - Sven Motz und Jens Averkamp GbR
 * http://www.two-developers.com
 *
 * Developer: Jens Averkamp
 * Date: 20.01.2015
 * Time: 14:31
 */

namespace WebsiteInfo\Parser;

use GuzzleHttp\Url;
use WebsiteInfo\Event\ParseResponseEvent;
use WebsiteInfo\WebsiteInfo;

class Lookup extends AbstractParser {

    public static function getSubscribedEvents()
    {
        return array(
            WebsiteInfo::EVENT_PARSE_RESPONSE => array('onParseResponse', -1),
        );
    }

    public function onParseResponse(ParseResponseEvent $event)
    {
        $url = Url::fromString($event->getRequest()->getUrl());
        $event->getData()->addSection('lookup', array(
            'ip' => gethostbynamel($url->getHost()),
            'hostname' => $url->getHost(),
            'dns' => dns_get_record($url->getHost(), DNS_ALL )
        ));
    }
}
