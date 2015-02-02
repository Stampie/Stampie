<?php

namespace Stampie\Carrier;

abstract class AbstractCarrier implements \Stampie\Carrier
{
    protected $key;

    /**
     * @param string $key
     */
    public function __construct($key)
    {
        $this->key = $key;
    }
}
