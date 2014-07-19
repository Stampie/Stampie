<?php

namespace Stampie\Spool;

use Stampie\Identity;
use Stampie\Message;

interface Storage
{
    public function push(Identity $to, Message $message);

    public function pop();
}
