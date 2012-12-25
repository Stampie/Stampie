<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stampie\Handler;

use Stampie\Message\Identity;
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
            'From'     => (string) $from,
            'To'       => (string) $to,
            'Subject'  => $message->getSubject(),
            'HtmlBody' => $message->getHtml(),
            'TextBody' => $message->getText(),
            'Headers'  => $message->getHeaders(),
        );

        $response = $this->adapter->request($this->endpoint, json_encode($parameters), array(
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'X-Postmark-Server-Token' => $this->key,
        ));
    }
}
