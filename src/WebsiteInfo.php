<?php
/**
 * Created by Two Developers - Sven Motz und Jens Averkamp GbR
 * http://www.two-developers.com
 *
 * Developer: Jens Averkamp
 * Date: 20.01.2015
 * Time: 13:35
 */

namespace WebsiteInfo;

use Saxulum\HttpClient\HttpClientInterface as ClientInterface;
use Saxulum\HttpClient\Request;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use TwoDevs\Cache\CacheInterface;
use WebsiteInfo\Event\ParseResponseEvent;
use WebsiteInfo\Exception\CannotReceiveInfoException;
use WebsiteInfo\Parser\ParserInterface;

class WebsiteInfo {

    const EVENT_PARSE_RESPONSE = 'zeichen32_websiteinfo.response.parse';
    const CACHE_PREFIX = 'zeichen32_websiteinfo_';

    /** @var ClientInterface  */
    private $client;

    /** @var EventDispatcherInterface  */
    private $dispatcher;

    /** @var \Doctrine\Common\Cache\Cache|null */
    private $cache;

    function __construct(ClientInterface $client, EventDispatcherInterface $dispatcher, array $parser = array())
    {
        $this->client = $client;
        $this->dispatcher = $dispatcher;

        $this->addDefaultParser($parser);
    }

    /**
     * @return ClientInterface
     */
    protected function getClient()
    {
        return $this->client;
    }

    /**
     * @return EventDispatcherInterface
     */
    protected function getDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * Add the default parser
     *
     * @param array $parser
     */
    protected function addDefaultParser(array $parser) {
        foreach($parser as $p) {
            if($p instanceof ParserInterface) {
                $this->addParser($p);
            }
        }
    }

    /**
     * @param ParserInterface $parser
     */
    public function addParser(ParserInterface $parser) {
        $this->getDispatcher()->addSubscriber($parser);
    }

    /**
     * @param ParserInterface $parser
     */
    public function removeParser(ParserInterface $parser) {
        $this->getDispatcher()->removeSubscriber($parser);
    }

    /**
     * @param CacheInterface $cache
     */
    public function setCache(CacheInterface $cache) {
        $this->cache = $cache;
    }

    protected function saveCache($key, $data, $lifetime = 120) {
        if(null !== $this->cache) {
            $this->cache->save($key, $data, $lifetime);
        }
    }

    protected function hasCached($key) {
        if(null !== $this->cache) {
            return $this->cache->contains($key);
        }

        return false;
    }

    protected function getCached($key) {
        if(null !== $this->cache) {
            return $this->cache->fetch($key);
        }

        return false;
    }

    protected function removeCached($key) {
        if(null !== $this->cache) {
            $this->cache->delete($key);
        }
    }

    public function get($url, $headers = [], $cacheLifetime = 120) {

        $cacheKey = self::CACHE_PREFIX . md5($url);

        if($this->hasCached($cacheKey)) {
            $data = json_decode($this->getCached($cacheKey), true);
            return new WebsiteInfoContainer($data);
        }

        try {
            $request = new Request('1.1', Request::METHOD_GET, $url, $headers);
            $response = $this->getClient()->request($request);
        } catch( \Exception $exp) {
            throw new CannotReceiveInfoException( 'Cannot receive website informations', 0, $exp );
        }

        $container = new WebsiteInfoContainer();
        $container->addSection('headers', ['request' => $request->getHeaders(), 'response' => $response->getHeaders()]);

        $this->getDispatcher()->dispatch(
            self::EVENT_PARSE_RESPONSE,
            new ParseResponseEvent($this->getClient(), $request, $response, $container)
        );

        $this->saveCache($cacheKey, json_encode($container->getSections()), $cacheLifetime);

        return $container;
    }
}
