<?php

namespace Stampie\Carrier;

use Stampie\Adapter\Request;
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
    public function send(Identity $to, Message $message)
    {
        $request = new Request($this->endpoint, 'POST');
        $request->setContent($this->format($to, $message));

        $this->prepare($request);

        $response = $this->adapter->request($request);

        if ($response->isSuccessful()) {
            $content = json_decode($response->getContent());

            // Mandril returns an array for each recipient
            return array_map([$this, 'pluckMessageId'], $content);
        }

        throw new \RuntimeException($response->getContent());
    }

    /**
     * {@inheritdoc}
     */
    protected function prepare(Request $request)
    {
        $request->setHeaders([
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function format(Identity $to, Message $message)
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
