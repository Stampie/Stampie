<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stampie;

use Stampie\Event\FailedMessageEvent;
use Stampie\Event\MessageEvent;
use Stampie\Message\MessageHeader;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Handles the sending of messages by proxying the message to the right handler
 * implementation.
 *
 * Through out the process there is dispatched events. This enables extensions
 * to integrate deeply.
 *
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 * @author Christophe Coevoet <stof@notk.org>
 * @package Stampie
 */
class Mailer
{
    protected $handler;
    protected $dispatcher;

    /**
     * @param Handler $handler
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(Handler $handler, EventDispatcherInterface $dispatcher = null)
    {
        $this->handler = $handler;
        $this->dispatcher = $dispatcher ?: new EventDispatcher;
    }

    /**
     * Sends the message to the handler and emits some events. When an even prevents default
     * a DefferedResult will be returned otherwise a the Result object from the handler is
     * returned. If sending fails a failed event will be emitted and the exception rethrown.
     *
     * @param Identity $to
     * @param Message $message
     * @return boolean
     */
    public function send(Identity $to, Message $message)
    {
        $event = $this->dispatcher->dispatch(Events::SEND, new MessageEvent($to, $message));

        if ($event->isDefaultPrevented()) {
            return new MessageHeader(null);
        }

        try {
            $messageId = $this->handler->send($event->getTo(), $event->getMessage());

            return new MessageHeader($messageId);
        } catch (\Exception $e) {
            // When a message fails should be just ignore the exception and handle it with
            // an event an return nothing?
            $this->dispatcher->dispatch(Events::FAILED, new FailedMessageEvent($to, $message, $e));
        }
    }
}
