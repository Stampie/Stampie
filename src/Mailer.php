<?php

namespace Stampie;

interface Mailer
{
    /**
     * @param  Recipient $to
     * @param  Message   $message
     * @return boolean
     */
    public function send(Recipient $to, Message $message);
}
