<?php

namespace Stampie;

interface Mailer
{
    /**
     * @param Identity $to
     * @param Message $message
     * @return boolean
     */
    public function send(Identity $to, Message $message);
}
