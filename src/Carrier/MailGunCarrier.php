<?php

namespace Stampie\Carrier;

use Stampie\Message;
use Stampie\Recipient;
use Stampie\Request;
use Stampie\Response;

class MailGunCarrier extends AbstractCarrier
{
    public function createRequest(Recipient $to, Message $message)
    {
        list($domain, $token) = explode(':', $this->key);

        return Request::create('https://api.mailgun.net/v2/' . $domain . '/messages')
            ->setContent($this->format($to, $message))
            ->setHeaders([
                'Authorization' => 'Basic ' . base64_encode('api:' . $token),
            ])
        ;
    }

    public function handleResponse(Response $response)
    {
        if ($response->isSuccessful()) {
            return json_decode($response->getContent())->id;
        }

        throw new \LogicException('Something Happended');
    }

    private function format(Recipient $to, Message $message)
    {
        return http_build_query([
            'to'      => (string) $to,
            'from'    => (string) $message->getFrom(),
            'subject' => $message->getSubject(),
            'text'    => $message->getText(),
            'html'    => $message->getHtml(),
        ]);
    }
}
