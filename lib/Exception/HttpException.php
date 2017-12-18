<?php

namespace Stampie\Exception;

use Stampie\ExceptionInterface;

/**
 * Exception thrown for all HTTP Error codes where the Api's doesn't themselves provide an error
 * message.
 *
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class HttpException extends \RuntimeException implements ExceptionInterface
{
    /**
     * @param int        $statusCode
     * @param string     $message
     * @param \Exception $previous
     * @param int        $code
     */
    public function __construct($statusCode, $message = null, \Exception $previous = null, $code = 0)
    {
        $this->statusCode = $statusCode;

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
