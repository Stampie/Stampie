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

    /**
     * @param string $url
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
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
     * Returns the raw http request that was send.
     *
     * @return string
     */
    public function __toString()
    {
        $parts = parse_url($this->url);

        $lines = [
            'POST ' . $parts['path'] . ' HTTP/1.0',
            'Host: ' . $parts['host'],
        ];

        foreach ($this->headers as $key => $value) {
            $lines[] = sprintf('%s: %s', $key, $value);
        }

        return implode("\n", $lines) . "\n\n" . $this->content;
    }
}
