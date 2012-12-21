<?php

namespace Stampie\Event;

use Stampie\Message\MessageInterface;

/**
 * @author Christophe Coevoet <stof@notk.org>
 */
class MessageEvent
{
    protected $message;
    protected $identity;

    /**
     * @param Message $message
     */
    public function __construct(MessageInterface $message)
    {
        $this->message = $message;
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
}
