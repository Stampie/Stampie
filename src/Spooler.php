<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stampie;

use Stampie\Spool\Storage;

final class Spooler implements Mailer
{
    private $mailer;
    private $storage;

    public function __construct(Mailer $mailer, Storage $storage)
    {
        $this->mailer = $mailer;
        $this->storage = $storage;
    }

    public function send(Recipient $to, Message $message)
    {
        $this->storage->push($to, $message);
    }

    public function flushSpool()
    {
        while ($item = $this->storage->pop()) {
            // What happens if a sending fails?
            $this->mailer->send($item[0], $item[1]);
        }
    }
}
