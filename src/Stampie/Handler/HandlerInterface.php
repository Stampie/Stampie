<?php

namespace Stampie\Handler;

use Stampie\Identity;
use Stampie\Message\MessageInterface;

/**
 * @package Stampie
 */
interface HandlerInterface
{
    /**
     * @param Identity $to
     * @param MessageInterface $message
     */
    public function send(Identity $to, MessageInterface $message);
}
