<?php

namespace Stampie\Mailer;

use Stampie\Mailer;
use Stampie\MessageInterface;
use Stampie\Adapter\ResponseInterface;
use Stampie\Exception\HttpException;
use Stampie\Exception\ApiException;

/**
 * Sends emails to Postmark server
 *
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class Postmark extends Mailer
{
    /**
     * @return string
     */
    public function getEndpoint()
    {
        return 'http://api.postmarkapp.com/email';
    }

    /**
     * @param ResponseInterace
     * @throws \LogicException
     */
    public function handle(ResponseInterface $response)
    {
        $httpException = new HttpException($response->getStatusCode(), $response->getStatusText());

        // Not 422 contains information about API Error
        if ($response->getStatusCode() == 422) {
            $error = json_decode($response->getContent());
            throw new ApiException($error->Message, $httpException);
        }

        throw $httpException;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return array(
            'Content-Type' => 'application/json',
            'X-Postmark-Server-Token' => $this->getServerToken(),
        );
    }

    /**
     * @param MessageInterface $message
     * @return string
     */
    public function format(MessageInterface $message)
    {
        $parameters = array_filter(array(
            'From'     => $message->getFrom(),
            'To'       => $message->getTo(),
            'Subject'  => $message->getSubject(),
            'Headers'  => $message->getHeaders(),
            'TextBody' => $message->getText(),
            'HtmlBody' => $message->getHtml(),
        ));

        return json_encode($parameters);
    }
}
