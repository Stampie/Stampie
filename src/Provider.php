<?php

namespace Stampie;

interface Provider
{
    /**
     * @param Identity         $to
     * @param MessageInterface $message
     */
    public function send(Identity $to, Message $message);
}
