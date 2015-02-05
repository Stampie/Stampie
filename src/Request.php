<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stampie;

class Request
{
    private $url;
    private $content;
    private $headers = [];

    /**
     * @param string $url
     * @param string $content
     * @param array  $headers
     */
    public function __construct($url, $content = '', $headers = [])
    {
        $this->url = $url;
        $this->content = $content;
        $this->headers = $headers;
    }

    /**
     * @param string $url
     * @param string $content
     * @param array  $headers
     *
     * @return Request
     */
    public static function create($url, $content = '', $headers = [])
    {
        return new self($url, $content, $headers);
    }

    /**
     * @param string $content
     *
     * @return self
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
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
     *
     * @return self
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @param array $headers
     *
     * @return self
     */
    public function addHeaders(array $headers)
    {
        $this->headers = array_replace($this->headers, $headers);

        return $this;
    }
}
