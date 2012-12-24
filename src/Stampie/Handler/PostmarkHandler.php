<?php

namespace Stampie\Handler;

use Stampie\Identity;
use Stampie\Message\MessageInterface;

class PostmarkHandler extends Handler
{
    protected $endpoint = 'http://api.postmarkapp.com/email';

    public function send(Identity $to, MessageInterface $message)
    {
        $from = $message->getFrom();

        $parameters = array(
            'From' => 'henrik@bjrnskov.dk',
            'To' => 'henrik@bjrnskov.dk',
            'Subject' => 'my subject',
            'HtmlBody' => '<p>html</p>',
            'TextBody' => 'text',
        );

        $this->adapter->request($this->endpoint, json_encode($parameters), array(
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'X-Postmark-Server-Token' => $this->key,
        ));
    }
}
