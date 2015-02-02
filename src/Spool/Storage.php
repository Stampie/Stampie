<?php

namespace Stampie\Spool;

use Stampie\Message;
use Stampie\Recipient;

interface Storage
{
    public function push(Recipient $to, Message $message);

    public function pop();
}
