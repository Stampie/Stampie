<?php

namespace Stampie\Adapter;

/**
 * @package Stampie
 */
class Response
{
    protected $statusCode;
    protected $content;
    protected $headers;

    /**
     * @param integer $statusCode
     * @param string $content
     * @param array $headers
     */
    public function __construct($statusCode, $content, array $headers = array())
    {
        $this->statusCode = $statusCode;
        $this->content = $content;
        $this->headers = $headers;
    }

    /**
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }
}
