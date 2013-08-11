<?php

namespace Stampie\Event;

use Stampie\Message\Identity;
use Stampie\Message;

/**
 * @package Stampie
 */
class FailedMessageEvent extends MessageEvent
{
    protected $exception;

    /**
     * @param Identity $to
     * @param Message $message
     * @param Exception $exception
     */
    public function __construct(Identity $to, Message $message, \Exception $exception)
    {
        parent::__construct($to, $message);

        $this->exception = $exception;
    }

    /**
     * @return Exception
     */
    public function getException()
    {
        return $this->exception;
    }
}
