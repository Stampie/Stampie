<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Stampie\Message;

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
