<?php

namespace Stampie;

use Stampie\Message\Identity;
use Stampie\Message\MessageInterface;

/**
 * @package Stampie
 */
interface MailerInterface
{
    /**
     * @param Indentity        $to
     * @param MessageInterface $message
     */
    public function send(Identity $to, MessageInterface $message);
}
