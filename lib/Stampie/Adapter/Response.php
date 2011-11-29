<?php

namespace Stampie\Adapter;

/**
 * Immutable implementation of ResponseInterface
 *
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class Response implements ResponseInterface
{
    /**
     * @var integer
     */
    protected $statusCode;

    /**
     * @var string
     */
    protected $content;

    /**
     * @param integer $statusCode
     * @param string $content
     */
    public function __construct($statusCode, $content)
    {
        $this->statusCode = $statusCode;
        $this->content = $content;
    }

    /**
     * @return integer
     */
    public function getStatusCode()
    {
        return (integer) $this->statusCode;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return (string) $this->content;
    }
}
