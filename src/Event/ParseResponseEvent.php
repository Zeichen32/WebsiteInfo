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

use Saxulum\HttpClient\HttpClientInterface as ClientInterface;
use Saxulum\HttpClient\Request;
use Saxulum\HttpClient\Response;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\EventDispatcher\Event;
use WebsiteInfo\WebsiteInfoContainer;

class ParseResponseEvent extends Event {

    /** @var Request */
    private $request;

    /** @var Response */
    private $response;

    /** @var WebsiteInfoContainer */
    private $data;

    /** @var ClientInterface */
    private $client;

    /** @var  Crawler */
    private $crawler;

    function __construct(ClientInterface $client, Request $request, Response $response, WebsiteInfoContainer $data )
    {
        $this->client = $client;
        $this->request = $request;
        $this->response = $response;
        $this->data = $data;
        $this->crawler = new Crawler( (string) $response->getContent() );
    }

    /**
     * @return ClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return Response
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
