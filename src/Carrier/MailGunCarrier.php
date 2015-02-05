<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stampie\Carrier;

use Stampie\Message;
use Stampie\Recipient;
use Stampie\Request;
use Stampie\Response;

class MailGunCarrier extends AbstractCarrier
{
    /**
     * {@inheritDoc}
     */
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

    /**
     * {@inheritDoc}
     */
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
            'to'      => $to->formatAsAddress(),
            'from'    => $message->getFrom() ? $message->getFrom()->formatAsAddress() : null,
            'subject' => $message->getSubject(),
            'text'    => $message->getText(),
            'html'    => $message->getHtml(),
        ]);
    }
}
