<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stampie\Handler;

use Stampie\Adapter\Request;
use Stampie\Exception\UnauthorizedException;
use Stampie\Identity;
use Stampie\Message;
use Stampie\Utils;

/**
 * @package Stampie
 */
class PostmarkHandler extends AbstractHandler
{
    protected $endpoint = 'http://api.postmarkapp.com/email';

    /**
     * {@inheritDoc}
     */
    public function send(Identity $to, Message $message)
    {
        $request = new Request($this->endpoint, 'POST');
        $request->setContent($this->format($to, $message));

        $this->prepare($request);

        $response = $this->adapter->request($request);

        if ($response->isSuccessful()) {
            return json_decode($response->getContent())->MessageID;
        }

        throw Utils::convertResponseToException($response);
    }

    /**
     * {@inheritDoc}
     */
    protected function format(Identity $to, Message $message)
    {
        return json_encode(array(
            'To'       => (string) $to,
            'From'     => (string) $message->getFrom(),
            'Subject'  => $message->getSubject(),
            'HtmlBody' => $message->getHtml(),
            'TextBody' => $message->getText(),
            'Headers'  => $message->getHeaders(),
        ));
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
