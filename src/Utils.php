<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stampie;

use Stampie\Exception;
use Stampie\Response;

/**
 * Utility methods.
 */
class Utils
{
    /**
     * @param Response $response
     *
     * @return Exception|\RuntimeException
     */
    public static function convertResponseToException(Response $response)
    {
        switch (true) {
            case $response->isUnauthorized():
                return new Exception\UnauthorizedException;
            default:
                return new \RuntimeException;
        }
    }
}
