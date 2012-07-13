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
     * {@inheritdoc}
     */
    protected function getEndpoint()
    {
        return 'http://api.postmarkapp.com/email';
    }

    /**
     * {@inheritdoc}
     */
    protected function handle(ResponseInterface $response)
    {
        $httpException = new HttpException($response->getStatusCode(), $response->getStatusText());

        // Not 422 contains information about API Error
        if ($response->getStatusCode() == 422) {
            $error = json_decode($response->getContent());
            throw new ApiException(isset($error->Message) ? $error->Message : 'Unprocessable Entity', $httpException);
        }

        throw $httpException;
    }

    /**
     * {@inheritdoc}
     */
    protected function getHeaders()
    {
        return array(
            'Content-Type' => 'application/json',
            'X-Postmark-Server-Token' => $this->getServerToken(),
            'Accept' => 'application/json',
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function format(MessageInterface $message)
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
