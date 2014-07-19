<?php

namespace Stampie;

interface Carrier
{
    /**
     * @param Identity         $to
     * @param MessageInterface $message
     */
    public function send(Identity $to, Message $message);
}
