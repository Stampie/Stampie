<?php

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

    public function send(Identity $to, Message $message)
    {
        $this->storage->push($to, $message);
    }

    public function flushSpool()
    {
        while ($item = $this->storage->pop()) {
            print "called\n";
            // What happens if a sending fails?
            $this->mailer->send($item[0], $item[1]);
        }
    }
}
