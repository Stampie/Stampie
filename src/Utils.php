<?php

namespace Stampie;

use Stampie\Adapter\Response;
use Stampie\Exception;

/**
 * Utility methods.
 *
 * @package Stampie
 */
class Utils
{
    /**
     * @param Response $response
     * @return Exception
     */
    public static function convertResponseToException(Response $response)
    {
        switch (true) {
            case $response->isUnauthorized():
                return new Exception\UnauthorizedException();
            default:
                return new \RuntimeException;
        }
    }
}
