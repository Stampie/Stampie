<?php

namespace Stampie\Mailer;

use Stampie\MessageInterface;
use Stampie\Adapter\ResponseInterface;
use Stampie\Exception\HttpException;

/**
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class MailGun extends \Stampie\Mailer
{
    public function getEndpoint()
    {
        list($domain,) = explode(':', $this->getServerToken());

        return 'https://api.mailgun.net/v2/' . $domain . '/messages';
    }

    public function setServerToken($serverToken)
    {
        if (false === strpos($serverToken, ':')) {
            throw new \InvalidArgumentException('MailGun uses a "custom.domain.tld:key-hash" based ServerToken');
        }

        parent::setServerToken($serverToken);
    }

    public function getHeaders()
    {
        list(, $serverToken) = explode(':', $this->getServerToken());

        return array(
            'Authorization' => 'Basic ' . base64_encode('api:' . $serverToken),
        );
    }

    public function format(MessageInterface $message)
    {
        // Custom headers should be prefixed with h:X-My-Header
        $headers = $message->getHeaders();
        array_walk($headers, function (&$value, &$key) {
            $key = 'h:' . $key;
        });

        $parameters = array(
            'from'    => $message->getFrom(),
            'to'      => $message->getTo(),
            'subject' => $message->getSubject(),
            'text'    => $message->getText(),
            'html'    => $message->getHtml(),
            'cc'      => $message->getCc(),
            'bcc'     => $message->getBcc(),
        );

        return http_build_query(array_filter(array_merge($headers, $parameters))); 
    }

    public function handle(ResponseInterface $response)
    {
        throw new HttpException($response->getStatusCode(), $response->getStatusText());
    }
}
