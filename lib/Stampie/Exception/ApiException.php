<?php

namespace Stampie\Exception;

/**
 * SubException
 *
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class ApiException extends \RuntimeException
{
    /**
     * @param string $message
     * @param \Exception $previous
     * @param integer $code
     */
    public function __construct($message, \Exception $previous = null, $code = 0)
    {
        parent::__construct($message, $code, $previous);
    }
}
