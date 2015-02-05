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
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DispatcherAwareMailer implements Mailer
{
    private $mailer;
    private $dispatcher;

    public function __construct(Mailer $mailer, EventDispatcherInterface $dispatcher)
    {
        $this->mailer = $mailer;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param Recipient $to
     * @param Message   $message
     *
     * @throws \Exception
     *
     * @return MessageHeader|boolean
     */
    public function send(Recipient $to, Message $message)
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
