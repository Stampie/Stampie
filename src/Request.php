<?php

namespace Stampie;

/**
 * @package Stampie
 */
class Request
{
    private $url;
    private $content;
    private $headers = [];

    public function __construct($url, $content = '', $headers = [])
    {
        $this->url = $url;
        $this->content = $content;
        $this->headers = $headers;
    }

    public static function create($url, $content = '', $headers = [])
    {
        return new self($url, $content, $headers);
    }

    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function setHeaders(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    public function addHeaders(array $headers)
    {
        $this->headers = array_replace($this->headers, $headers);

        return $this;
    }
}
