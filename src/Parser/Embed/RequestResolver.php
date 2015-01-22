<?php
/**
 * Created by Two Developers - Sven Motz und Jens Averkamp GbR
 * http://www.two-developers.com
 *
 * Developer: Jens Averkamp
 * Date: 20.01.2015
 * Time: 21:00
 */

namespace WebsiteInfo\Parser\Embed;

use Embed\RequestResolvers\RequestResolverInterface;
use GuzzleHttp\Message\ResponseInterface;

class RequestResolver implements RequestResolverInterface{

    private $url;

    private $response;

    /**
     * Constructor. Sets the url
     *
     * @param string $url The url value
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Sets the configuration
     *
     * @param array $config
     */
    public function setConfig(array $config)
    {
        if(!isset($config['response']) || !$config['response'] instanceof ResponseInterface) {
            throw new \LogicException('You must set response option');
        }

        $this->response = $config['response'];
    }

    /**
     * Set a new url (and clear data of previous url)
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Get the http code of the url, for example: 200
     *
     * @return int The http code
     */
    public function getHttpCode()
    {
        return $this->response->getStatusCode();
    }

    /**
     * Get the content-type of the url, for example: text/html
     *
     * @return string The content-type header or null
     */
    public function getMimeType()
    {
        return $this->response->getHeader('Content-Type');
    }

    /**
     * Get the content of the url
     *
     * @return string The content or false
     */
    public function getContent()
    {
        return (string) $this->response->getBody();
    }

    /**
     * Return the final url (after all possible redirects)
     *
     * @return string The final url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Return the http request info (for debug purposes)
     *
     * @return array
     */
    public function getRequestInfo()
    {
        return array();
    }
}
