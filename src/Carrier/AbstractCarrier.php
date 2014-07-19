<?php

namespace Stampie\Carrier;

use Stampie\Adapter\Request;
use Stampie\Adapter\Response;
use Stampie\Message;
use Stampie\Message\MessageHeader;
use Stampie\Identity;

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
