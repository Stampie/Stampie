<?php

namespace Stampie\Event;

use Stampie\Identity;
use Stampie\Message\MessageInterface;

/**
 * @author Christophe Coevoet <stof@notk.org>
 */
class MessageEvent extends Event
{
    protected $message;
    protected $to;

    /**
     * @param Identity $to
     * @param Message $message
     */
    public function __construct(Identity $to, MessageInterface $message)
    {
        $this->message = $message;
        $this->to = $to;
    }

    /**
     * @param MessageInterface $message
     */
    public function setMessage(MessageInterface $message)
    {
        $this->message = $message;
    }

    /**
     * @return MessageInterface
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param Identity $to
     */
    public function setTo(Identity $to)
    {
        $this->to = $to;
    }

    /**
     * @return Identity
     */
    public function getTo()
    {
        return $this->to;
    }
}
