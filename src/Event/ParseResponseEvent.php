<?php
/**
 * Created by Two Developers - Sven Motz und Jens Averkamp GbR
 * http://www.two-developers.com
 *
 * Developer: Jens Averkamp
 * Date: 20.01.2015
 * Time: 13:46
 */

namespace WebsiteInfo\Event;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Message\ResponseInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\EventDispatcher\Event;
use WebsiteInfo\WebsiteInfoContainer;

class ParseResponseEvent extends Event {

    /** @var RequestInterface */
    private $request;

    /** @var ResponseInterface */
    private $response;

    /** @var WebsiteInfoContainer */
    private $data;

    /** @var ClientInterface */
    private $client;

    /** @var  Crawler */
    private $crawler;

    function __construct(ClientInterface $client, RequestInterface $request, ResponseInterface $response, WebsiteInfoContainer $data )
    {
        $this->client = $client;
        $this->request = $request;
        $this->response = $response;
        $this->data = $data;
        $this->crawler = new Crawler( (string) $response->getBody() );
    }

    /**
     * @return ClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /** @return WebsiteInfoContainer */
    public function getData() {
        return $this->data;
    }

    /** @return Crawler */
    public function getCrawler() {
        return $this->crawler;
    }
}
