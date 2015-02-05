<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stampie;

use Stampie\Event\FailedMessageEvent;
use Stampie\Event\SendMessageEvent;
use Stampie\Message\MessageHeader;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Handles the sending of messages by proxying the message to the right handler
 * implementation.
 *
 * Throughout the process there is dispatched events. This enables extensions
 * to integrate deeply.
 *
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 * @author Christophe Coevoet <stof@notk.org>
 */
class DirectMailer implements Mailer
{
    private $carrier;
    private $adapter;

    public function __construct(Carrier $carrier, Adapter $adapter)
    {
        $this->carrier = $carrier;
        $this->adapter = $adapter;
    }

    /**
     * Sends the message to the handler and emits some events. When an even prevents default
     * a DefferedResult will be returned otherwise a the Result object from the handler is
     * returned. If sending fails a failed event will be emitted and the exception rethrown.
     *
     * @param Recipient $to
     * @param Message   $message
     *
     * @return MessageHeader
     */
    public function send(Recipient $to, Message $message)
    {
        return new MessageHeader($this->doSend($to, $message));
    }

    private function doSend(Recipient $to, Message $message)
    {
        $request = $this->carrier->createRequest($to, $message);

        return $this->carrier->handleResponse($this->adapter->request($request));
    }
}
