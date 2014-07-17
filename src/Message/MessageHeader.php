<?php

namespace Stampie\Message;

/**
 * @package Stampie
 */
class MessageHeader
{
    protected $messageId;

    /**
     * @param string|integer|null $messageId
     */
    public function __construct($messageId)
    {
        $this->messageId = $messageId;
    }

    /**
     * @return string|integer|null
     */
    public function getMessageId()
    {
        return $this->messageId;
    }
}
