<?php
/**
 * Created by Two Developers - Sven Motz und Jens Averkamp GbR
 * http://www.two-developers.com
 *
 * Developer: Jens Averkamp
 * Date: 21.01.2015
 * Time: 11:23
 */

use GuzzleHttp\Client;
use Symfony\Component\EventDispatcher\EventDispatcher;

require_once dirname(__DIR__) . '/../vendor/autoload.php';

abstract class AbstractParserTest extends PHPUnit_Framework_TestCase {

    /** @var Client */
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
     * @return Client
     */
    protected function getClientWithResponse($body, $statusCode = 200, $headers = array()) {

        $this->client = new Client();
        $body = \GuzzleHttp\Stream\Stream::factory($body);
        $mock = new \GuzzleHttp\Subscriber\Mock(array(
            new \GuzzleHttp\Message\Response($statusCode, $headers, $body)
        ));

        $this->client->getEmitter()->attach($mock);

        return $this->client;
    }
}
