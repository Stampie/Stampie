<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stampie\Event;

use Stampie\Identity;
use Stampie\Message;

/**
 * @author Christophe Coevoet <stof@notk.org>
 */
class MessageEvent extends Event
{
    protected $message;
    protected $to;

    /**
     * @param Identity $to
     * @param Message  $message
     */
    public function __construct(Identity $to, Message $message)
    {
        $this->message = $message;
        $this->to = $to;
    }

    /**
     * @param Message $message
     */
    public function setMessage(Message $message)
    {
        $this->message = $message;
    }

    /**
     * @return Message
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
