<?php

namespace Stampie;

interface MailerInterface
{
    /**
     * Sends an email message.
     *
     * @param MessageInterface $message
     *
     * @throws ExceptionInterface if an error happens during sending the message.
     */
    public function send(MessageInterface $message);
}
