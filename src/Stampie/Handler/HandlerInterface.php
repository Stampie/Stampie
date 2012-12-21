<?php

namespace Stampie\Handler;

use Stampie\Message\MessageInterface;

/**
 * @package Stampie
 */
interface HandlerInterface
{
    public function send(MessageInterface $message);
}
