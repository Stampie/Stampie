<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stampie\Handler;

use Stampie\Identity;
use Stampie\Message\MessageInterface;

/**
 * @package Stampie
 */
class PostmarkHandler extends Handler
{
    protected $endpoint = 'http://api.postmarkapp.com/email';

    /**
     * {@inheritDoc}
     */
    public function send(Identity $to, MessageInterface $message)
    {
        $from = $message->getFrom();

        $parameters = array(
            'From'     => $from->email,
            'To'       => $to->email,
            'Subject'  => $message->getSubject(),
            'HtmlBody' => $message->getHtml(),
            'TextBody' => $message->getText(),
        );

        $response = $this->adapter->request($this->endpoint, json_encode($parameters), array(
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'X-Postmark-Server-Token' => $this->key,
        ));
    }
}
