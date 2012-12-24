<?php

namespace Stampie\Handler;

use Stampie\Adapter\AdapterInterface;

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
