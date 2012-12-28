<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stampie\Handler;

use Stampie\Adapter\AdapterInterface;
use Stampie\Adapter\Request;

/**
 * @package Stampie
 */
abstract class Handler implements HandlerInterface
{
    protected $key;
    protected $adapter;

    /**
     * @param AdapterInterface $adapter
     * @param string           $key
     */
    public function __construct(AdapterInterface $adapter, $key)
    {
        $this->adapter = $adapter;
        $this->key = $key;
    }
}
