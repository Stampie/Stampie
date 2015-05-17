<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stampie;

interface Mailer
{
    /**
     * @param Recipient $to
     * @param Message   $message
     *
     * @return boolean
     */
    public function send(Recipient $to, Message $message);
}
