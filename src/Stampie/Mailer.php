<?php

namespace Stampie;

use Stampie\Event\MessageEvent;
use Stampie\Handler\HandlerInterface;
use Stampie\Message\MessageInterface;
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
final class Mailer
{
    protected $handler;
    protected $dispatcher;

    /**
     * @param HandlerInterface $handler
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(HandlerInterface $handler, EventDispatcherInterface $dispatcher = null)
    {
        $this->handler = $handler;
        $this->dispatcher = $dispatcher ?: new EventDispatcher();
    }

    /**
     * @param Indentity $to
     * @param MessageInterface $message
     */
    public function send(Identity $to, MessageInterface $message)
    {
        $event = $this->dispatcher->dispatch(Events::SEND, new MessageEvent($to, $message));

        if (false == $event->isDefaultPrevented()) {
            $this->handler->send($event->getTo(), $event->getMessage());
        }

        // $event = $this->dispatcher->dispatch(Events::SEND_FAILED', new MessageEvent($to, $message));
    }
}
