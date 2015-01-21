<?php
/**
 * Created by Two Developers - Sven Motz und Jens Averkamp GbR
 * http://www.two-developers.com
 *
 * Developer: Jens Averkamp
 * Date: 20.01.2015
 * Time: 20:59
 */

namespace WebsiteInfo\Parser\Embed;


use WebsiteInfo\Event\ParseResponseEvent;
use WebsiteInfo\Parser\AbstractParser;

class Embed extends AbstractParser {

    public function onParseResponse(ParseResponseEvent $event)
    {
        $content = \Embed\Embed::create($event->getRequest()->getUrl(), array(
            "resolver" => array(
                "class" => 'WebsiteInfo\Parser\Embed\RequestResolver',
                'options' => array(
                    'response' => $event->getResponse()
                )
            ))
        );

        if($content) {
            $event->getData()->addSection('embed', array(
                'title' => $content->getTitle(),
                'description' => $content->getDescription(),
                'url' => $content->getUrl(),
                'type' => $content->getType(),
                'embed_code' => $content->getCode(),
                'images' => array(
                    'collection' => $content->getImages(),
                    'base' => array(
                        'image' => $content->getImage(),
                        'width' => $content->getImageWidth(),
                        'height' => $content->getImageHeight(),
                        'aspect_ration' => $content->getAspectRatio(),
                    )
                ),
                'author' => array(
                    'name' => $content->getAuthorName(),
                    'url' => $content->getAuthorUrl(),
                ),
                'provider' => array(
                    'name' => $content->getProviderName(),
                    'url' => $content->getProviderUrl(),
                    'icon' => $content->getProviderIcon(),
                    'icons' => $content->getProviderIcons(),
                ),
            ));
        }
    }

}
