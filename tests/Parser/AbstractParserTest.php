<?php
/**
 * Created by Two Developers - Sven Motz und Jens Averkamp GbR
 * http://www.two-developers.com
 *
 * Developer: Jens Averkamp
 * Date: 21.01.2015
 * Time: 11:23
 */

use Saxulum\HttpClient\HttpClientInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

require_once dirname(__DIR__) . '/../vendor/autoload.php';

abstract class AbstractParserTest extends PHPUnit_Framework_TestCase {

    /** @var HttpClientInterface */
    protected $client;

    /** @var EventDispatcher */
    protected $dispatcher;

    protected function setUp()
    {
        $this->dispatcher = new EventDispatcher();
    }

    /**
     * @param $body
     * @param int $statusCode
     * @param array $headers
     * @return HttpClientInterface
     */
    protected function getClientWithResponse($body, $statusCode = 200, $headers = array()) {

        $guzzle = new \GuzzleHttp\Client();
        $body = \GuzzleHttp\Stream\Stream::factory($body);
        $mock = new \GuzzleHttp\Subscriber\Mock(array(
            new \GuzzleHttp\Message\Response($statusCode, $headers, $body)
        ));
        $guzzle->getEmitter()->attach($mock);

        $this->client = new \Saxulum\HttpClient\Guzzle\HttpClient($guzzle);
        return $this->client;
    }
}
