<?php

namespace Stampie\Mailer;

use Stampie\Mailer;
use Stampie\MessageInterface;
use Stampie\Adapter\ResponseInterface;
use Stampie\Exception\HttpException;
use Stampie\Exception\ApiException;

/**
 * Mailer to be used with SendGrid Web API
 *
 * @author Henrik Bjrnskov <henrik@bjrnskov.dk>
 */
class SendGrid extends Mailer
{
    /**
     * @return string
     */
    public function getEndpoint()
    {
        return 'https://sendgrid.com/api/mail.send.json';
    }

    /**
     * @param string $serverToken
     * @throws \InvalidServerToken
     */
    public function setServerToken($serverToken)
    {
        if (false === strpos( $serverToken, ':')) {
            throw new \InvalidArgumentException('SendGrid uses a "username:password" based ServerToken');
        }

        parent::setServerToken($serverToken);
    }

    /**
     * @param ResponseInterface
     */
    public function handle(ResponseInterface $response)
    {
        $statusCode = $response->getStatusCode();
        $httpException = new HttpException($statusCode, $response->getStatusText());

        if (substr($statusCode, 0, 1) == 5) {
            throw $httpException;
        }

        $error = json_decode($response->getContent());
        $errors = isset($error->errors) ? (array) $error->errors : array();

        throw new ApiException(implode(', ', $errors), $httpException);
    }

    /**
     * @param MessageInterface
     * @return string
     */
    public function format(MessageInterface $message)
    {
        // We should split up the ServerToken on : to get username and password
        list($username, $password) = explode(':', $this->getServerToken());

        $parameters = array(
            'api_user' => $username,
            'api_key'  => $password,
            'to'       => $message->getTo(),
            'from'     => $message->getFrom(),
            'subject'  => $message->getSubject(),
            'text'     => $message->getText(),
            'html'     => $message->getHtml(),
            'bcc'      => $message->getBcc(),
            'replyto'  => $message->getReplyTo(),
            'headers'  => json_encode($message->getHeaders()),
        );

        return http_build_query(array_filter($parameters));
    }
}
