<?php

namespace Stampie\Exception;

/**
 * Exception thrown for all HTTP Error codes where the Api's doesn't themselves provide an error
 * message.
 *
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class HttpException extends \RuntimeException
{
    /**
     * @param integer    $statusCode
     * @param string     $message
     * @param \Exception $previous
     * @param integer    $code
     */
    public function __construct($statusCode, $message = null, \Exception $previous = null, $code = 0)
    {
        $this->statusCode = $statusCode;

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
