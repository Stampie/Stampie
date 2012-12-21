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
     * @param MessageInterface $message
     */
    public function send(MessageInterface $message)
    {
        $event = $this->dispatcher->dispatch(Events::PRE_SEND, new MessageEvent($message));

        $this->handler->send($this->adapter, $event->getMessage());
    }
}
