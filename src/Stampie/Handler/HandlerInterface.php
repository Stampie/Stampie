<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stampie\Handler;

use Stampie\Message\Identity;
use Stampie\Message\MessageInterface;

/**
 * @package Stampie
 */
interface HandlerInterface
{
    /**
     * @param Identity $to
     * @param MessageInterface $message
     */
    public function send(Identity $to, MessageInterface $message);
}
