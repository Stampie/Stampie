<?php

namespace Stampie\Message;

use Stampie\Identity;

/**
 * @package Stampie
 */
class Message implements MessageInterface
{
    public function getHtml()
    {
        return '<p>html</p>';
    }

    public function getText()
    {
        return 'text';
    }

    public function getFrom()
    {
        return new Identity('henrik@bjrnskov.dk', 'Henrik Bjørnskov');
    }

    public function getSubject()
    {
        return 'Stampie2';
    }
}
