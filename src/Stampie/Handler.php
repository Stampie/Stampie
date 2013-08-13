<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stampie;

use Stampie\Identity;

/**
 * @package Stampie
 */
interface Handler
{
    /**
     * @param Identity         $to
     * @param MessageInterface $message
     */
    public function send(Identity $to, Message $message);
}
