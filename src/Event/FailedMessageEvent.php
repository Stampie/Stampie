<?php

namespace Stampie\Event;

use Stampie\Message;
use Stampie\Recipient;

/**
 * @package Stampie
 */
class FailedMessageEvent extends AbstractMessageEvent
{
    protected $exception;

    /**
     * @param Recipient  $to
     * @param Message    $message
     * @param \Exception $exception
     */
    public function __construct(Recipient $to, Message $message, \Exception $exception)
    {
        parent::__construct($to, $message);

        $this->exception = $exception;
    }

    /**
     * @return \Exception
     */
    public function getException()
    {
        return $this->exception;
    }
}
