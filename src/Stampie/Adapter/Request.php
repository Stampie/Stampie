<?php

namespace Stampie\Adapter;

/**
 * @package Stampie
 */
class Request
{
    protected $url;
    protected $method;
    protected $body;
    protected $headers = array();

    /**
     * @param string $url
     * @param string $method
     */
    public function __construct($url, $method = 'POST')
    {
        $this->url = $url;
        $this->method = $method;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }

    /**
     * @param array $headers
     */
    public function addHeaders(array $headers)
    {
        $this->headers = array_replace($this->headers, $headers);
    }

    /**
     * @return string
     */
    public function __toString()
    {

    }
}
