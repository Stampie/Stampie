<?php

namespace Stampie\Adapter;

/**
 * @package Stampie
 */
class Response
{
    private $statusCode;
    private $content;
    private $headers;

    /**
     * @param integer $statusCode
     * @param string $content
     * @param array $headers
     */
    public function __construct($statusCode, $content, array $headers = [])
    {
        $this->statusCode = $statusCode;
        $this->content    = $content;
        $this->headers    = $headers;
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

    /**
     * @return boolean
     */
    public function isSuccessful()
    {
        return $this->getStatusCode() >= 200 && $this->getStatusCode() < 300;
    }

    /**
     * @return boolean
     */
    public function isServerError()
    {
        return $this->getStatusCode() >= 500 && $this->getStatusCode() < 600;
    }

    /**
     * @return boolean
     */
    public function isClientError()
    {
        return $this->getStatusCode() >= 400 && $this->getStatusCode() < 500;
    }

    /**
     * @return boolean
     */
    public function isUnauthorized()
    {
        return $this->getStatusCode() == 401;
    }
}
