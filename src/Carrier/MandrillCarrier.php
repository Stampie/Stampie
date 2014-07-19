<?php

namespace Stampie\Carrier;

use Stampie\Request;
use Stampie\Response;
use Stampie\Message;
use Stampie\Identity;

/**
 * Sends emails to Mandrill server
 *
 * @author Christophe Coevoet <stof@notk.org>
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class MandrillCarrier extends AbstractCarrier
{
    protected $endpoint = 'https://mandrillapp.com/api/1.0/messages/send.json';

    /**
     * {@inheritdoc}
     */
    public function createRequest(Identity $to, Message $message)
    {
        $request = new Request($this->endpoint, 'POST');
        $request->setContent($this->format($to, $message));
        $request->setHeaders([
            'Content-Type' => 'application/json',
        ]);

        return $request;
    }

    public function handleResponse(Response $response)
    {
        if (!$response->isSuccessful()) {
            throw new \RuntimeException($response->getContent());
        }

        // Mandril returns an array for each recipient
        return array_map([$this, 'pluckMessageId'], json_decode($response->getContent()));
    }

    private function format(Identity $to, Message $message)
    {
        $from = $message->getFrom();

        $parameters = [
            'key'     => $this->key,
            'message' => array_filter([
                'to' => [[
                    'email' => $to->email,
                    'name' => $to->name,
                ]],
                'from_email' => $from->email,
                'from_name'  => $from->name,
                'subject'    => $message->getSubject(),
                'headers'    => $message->getHeaders(),
                'text'       => $message->getText(),
                'html'       => $message->getHtml(),
                'async'      => true,
            ]),
        ];

        return json_encode($parameters);
    }

    private function pluckMessageId($result)
    {
        return $result->_id;
    }

    private function formatAttachment(Attachment $attachment)
    {
        return [
            'type'    => $attachment->getContentType(),
            'name'    => $attachment->getName(),
            'content' => $attachment->getEncodedContent(),
        ];
    }
}
