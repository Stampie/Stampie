<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stampie\Handler;

use Stampie\Adapter;
use Stampie\Adapter\Request;
use Stampie\Message;
use Stampie\Identity;

/**
 * @package Stampie
 */
abstract class AbstractHandler implements \Stampie\Handler
{
    protected $key;
    protected $adapter;

    /**
     * @param AdapterInterface $adapter
     * @param string           $key
     */
    public function __construct(Adapter $adapter, $key)
    {
        $this->adapter = $adapter;
        $this->key = $key;
    }

    /**
     * Used to format a message and identity into a string representation.
     * Normally this will be json or a query string.
     *
     * @param Identity $to
     * @param Message $message
     */
    abstract protected function format(Identity $to, Message $message);

    /**
     * Used to set additional headers or if the API key is
     * required in the request.
     *
     * @param Request $request
     */
    abstract protected function prepare(Request $request);
}
