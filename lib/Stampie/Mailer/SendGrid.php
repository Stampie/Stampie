<?php

namespace Stampie\Mailer;

use Stampie\Mailer;
use Stampie\MessageInterface;
use Stampie\Adapter\ResponseInterface;

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
    protected function handle(ResponseInterface $response)
    {
        print_r($response);die;
    }

    /**
     * @param MessageInterface
     * @return string
     */
    protected function format(MessageInterface $message)
    {
        // We should split up the ServerToken on : to get username and password
        list($user, $password) = explode(':', $this->getServerToken());

        $parameters = array(
            'to'      => $message->getTo(),
            'from'    => $message->getFrom(),
            'subject' => $message->getSubject(),
            'text'    => $message->getText(),
            'html'    => $message->getHtml(),
            'bcc'     => $message->getBcc(),
            'replyto' => $message->getReplyTo(),
            'headers' => json_encode($message->getHeaders()),
        );

        return http_build_query(array_filter($parameters));
    }

    /**
     * @return array
     */
    protected function getHeaders()
    {
        return array(
            'Content-Type' => 'multipart/form-data',
        );
    }
}
