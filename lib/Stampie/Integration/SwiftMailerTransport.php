<?php

namespace Stampie\Integration;

use Stampie\MailerInterface;
use Stampie\Message;

/**
 * A SwiftMailer Transport class for Stampie.
 *
 * @author Andreas Hucks <andreas.hucks@duochrome.net>
 */
class SwiftMailerTransport implements \Swift_Transport
{
    /**
     * @var Stampie\MailerInterface
     */
    private $stampie;

    /**
     * @var Swift_Events_EventDispatcher
     */
    private $dispatcher;

    public function __construct(MailerInterface $stampie, \Swift_Events_EventDispatcher $dispatcher)
    {
        $this->stampie = $stampie;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Not used.
     *
     * @return boolean
     */
    public function isStarted()
    {
        return false;
    }

    /**
     * Not used.
     */
    public function start() {}

    /**
     * Not used.
     */
    public function stop() {}

    /**
     * Send the given Message.
     *
     * Recipient/sender data will be retrieved from the Message API.
     * The return value is the number of recipients who were accepted for delivery.
     *
     * @param Swift_Mime_Message $message
     * @param string[] &$failedRecipients to collect failures by-reference
     * @return int
     */
    public function send(\Swift_Mime_Message $message, &$failedRecipients = null)
    {
        $failedRecipients = (array)$failedRecipients;

        if ($event = $this->dispatcher->createSendEvent($this, $message)) {
            $this->dispatcher->dispatchEvent($event, 'beforeSendPerformed');
            if ($event->bubbleCancelled()) {
                return 0;
            }
        }

        $count =
            count((array)$message->getTo()) + count((array)$message->getCc()) + count((array)$message->getBcc());

        // TODO: convert possible exceptions to \Swift_TransportException?
        $success = $this->stampie->send($this->getStampieMessage($message));

        if ($event) {
            $event->setResult($success ? \Swift_Events_SendEvent::RESULT_SUCCESS : \Swift_Events_SendEvent::RESULT_FAILED);
            $this->dispatcher->dispatchEvent($event, 'sendPerformed');
        }

        return $success ? $count : 0;
    }

    /**
     * Register a plugin in the Transport.
     *
     * @param Swift_Events_EventListener $plugin
     */
    public function registerPlugin(\Swift_Events_EventListener $plugin)
    {
        $this->dispatcher->bindEventListener($plugin);
    }

    /**
     * @param \Swift_Mime_Message $message
     * @return \Stampie\MessageInterface
     */
    protected function getStampieMessage(\Swift_Mime_Message $message)
    {
        // solution?
    }

}
