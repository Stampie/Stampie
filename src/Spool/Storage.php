<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stampie\Spool;

use Stampie\Message;
use Stampie\Recipient;

interface Storage
{
    public function push(Recipient $to, Message $message);

    public function pop();
}
