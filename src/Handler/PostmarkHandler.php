<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stampie\Handler;

use Stampie\Adapter\Request;
use Stampie\Adapter\Response;
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
    protected function handleResponse(Response $response)
    {
        if ($response->isSuccessful()) {
            return json_decode($response->getContent())->MessageID;
        }

        throw Utils::convertResponseToException($response);
    }

    /**
     * {@inheritDoc}
     */
    protected function format(Identity $to, Message $message, $attachments = [])
    {
        $parameters = [
            'To'       => (string) $to,
            'From'     => (string) $message->getFrom(),
            'Subject'  => $message->getSubject(),
            'HtmlBody' => $message->getHtml(),
            'TextBody' => $message->getText(),
            'Headers'  => $message->getHeaders(),
        ];

        if ($attachments) {
            $parameters['Attachments'] = array_map([$this, 'formatAttachment'], $attachments);
        }

        return json_encode($parameters);
    }

    /**
     * {@inheritDoc}
     */
    protected function prepare(Request $request)
    {
        $request->setHeaders([
            'Accept'                  => 'application/json',
            'Content-Type'            => 'application/json',
            'X-Postmark-Server-Token' => $this->key,
        ]);
    }

    private function formatAttachment(Attachment $attachment)
    {
        return [
            'Name'        => $attachment->getName(),
            'Content'     => $attachment->getEncodedContent(),
            'ContentType' => $attachment->getContentType(),
        ];
    }
}
