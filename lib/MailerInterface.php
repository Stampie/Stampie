<?php

namespace Stampie;

interface MailerInterface
{
    /**
     * Sends an email message.
     *
     * @param MessageInterface $message
     *
     * @throws \Exception if an error happens during sending the message.
     */
    public function send(MessageInterface $message);
}
