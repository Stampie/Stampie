<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stampie\Handler;

use Stampie\Adapter\Request;
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
        // Should this be moved into a format message?
        $parameters = array(
            'To'       => (string) $to,
            'From'     => (string) $message->getFrom(),
            'Subject'  => $message->getSubject(),
            'HtmlBody' => $message->getHtml(),
            'TextBody' => $message->getText(),
            'Headers'  => $message->getHeaders(),
        );

        $request = new Request($this->endpoint, 'POST');
        $request->setContent(json_encode($parameters));

        $this->prepare($request);

        $response = $this->adapter->call($request);
    }

    /**
     * {@inheritDoc}
     */
    protected function prepare(Request $request)
    {
        $request->setHeaders(array(
            'Accept'                  => 'application/json',
            'Content-Type'            => 'application/json',
            'X-Postmark-Server-Token' => $this->key,
        ));
    }
}
