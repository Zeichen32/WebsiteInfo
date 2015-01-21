<?php
/**
 * Created by Two Developers - Sven Motz und Jens Averkamp GbR
 * http://www.two-developers.com
 *
 * Developer: Jens Averkamp
 * Date: 20.01.2015
 * Time: 13:50
 */

namespace WebsiteInfo\Parser\Webserver;

use WebsiteInfo\Event\ParseResponseEvent;
use WebsiteInfo\Parser\AbstractParser;

class Apache extends AbstractParser {

    public function onParseResponse(ParseResponseEvent $event)
    {
        $response = $event->getResponse();

        if($response->hasHeader('Server')) {

            $server = $response->getHeader('Server');

            if(false !== stristr($server, 'Apache')) {

                $version = $this->parseVersion($server);

                $event->getData()->addSection('webserver', array(
                    'name' => 'Apache',
                    'version' => $version,
                    'score' => ('unknown' == $version) ? 1 : 5,
                    'raw' => $server
                ));
            }
        }
    }

    private function parseVersion($str) {
        $version = preg_split("/\/+/", $str);
        if(count($version) == 2) {
            return $version[1];
        }

        return 'unknown';
    }
}
