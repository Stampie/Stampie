<?php

namespace Stampie\Exception;

use Stampie\ExceptionInterface;

/**
 * SubException.
 *
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class ApiException extends \RuntimeException implements ExceptionInterface
{
    /**
     * @param string     $message
     * @param \Exception $previous
     * @param int        $code
     */
    public function __construct($message, \Exception $previous = null, $code = 0)
    {
        parent::__construct($message, $code, $previous);
    }
}
