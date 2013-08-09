<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stampie;

use Stampie\Event\MessageEvent;
use Stampie\Handler\HandlerInterface;
use Stampie\Message\MessageInterface;
use Stampie\Message\Identity;
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
    public function __construct(Handler $handler, EventDispatcherInterface $dispatcher)
    {
        $this->handler = $handler;
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritDoc}
     */
    public function send(Identity $to, Message $message)
    {
        $event = $this->dispatcher->dispatch(Events::SEND, new MessageEvent($to, $message));

        if (!$event->isDefaultPrevented()) {
            $this->handler->send($event->getTo(), $event->getMessage());
        }

        // $event = $this->dispatcher->dispatch(Events::SEND_FAILED', new MessageEvent($to, $message));
    }
}
