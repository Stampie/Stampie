<?php

namespace Stampie;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Stampie\Event\SendMessageEvent;
use Stampie\Event\FailedMessageEvent;

class DispatcherAwareMailer implements Mailer
{
    private $mailer;
    private $dispatcher;

    public function __construct(Mailer $mailer, EventDispatcherInterface $dispatcher)
    {
        $this->mailer = $mailer;
        $this->dispatcher = $dispatcher;
    }

    public function send(Identity $to, Message $message)
    {
        $event = $this->dispatcher->dispatch(StampieEvents::SEND, new SendMessageEvent($to, $message));

        if ($event->isDefaultPrevented()) {
            return new MessageHeader(null);
        }

        try {
            return $this->mailer->send($to, $message);
        } catch (\Exception $e) {
            $this->dispatcher->dispatch(StampieEvents::FAILED, new FailedMessageEvent($to, $message, $e));

            throw $e;
        }
    }
}
