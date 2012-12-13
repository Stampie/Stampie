<?php

namespace Stampie\Mailer;

use Stampie\Mailer;
use Stampie\MessageInterface;
use Stampie\Message\TaggableInterface;
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
     * {@inheritdoc}
     */
    protected function getEndpoint()
    {
        return 'https://sendgrid.com/api/mail.send.json';
    }

    /**
     * {@inheritdoc}
     * @throws \InvalidArgumentException
     */
    public function setServerToken($serverToken)
    {
        if (false === strpos( $serverToken, ':')) {
            throw new \InvalidArgumentException('SendGrid uses a "username:password" based ServerToken');
        }

        parent::setServerToken($serverToken);
    }

    /**
     * {@inheritdoc}
     */
    protected function handle(ResponseInterface $response)
    {
        $httpException = new HttpException($response->getStatusCode(), $response->getStatusText());

        // 4xx will containt error information in the body encoded as JSON
        if (!in_array($response->getStatusCode(), range(400, 417))) {
            throw $httpException;
        }

        $error = json_decode($response->getContent());
        throw new ApiException(implode(', ', (array) $error->errors), $httpException);
    }

    /**
     * {@inheritdoc}
     */
    protected function format(MessageInterface $message)
    {
        // We should split up the ServerToken on : to get username and password
        list($username, $password) = explode(':', $this->getServerToken());

        $from = $this->normalizeIdentity($message->getFrom());
        $to = $this->normalizeIdentity($message->getTo());

        $parameters = array(
            'api_user' => $username,
            'api_key'  => $password,
            'to'       => $to->getEmail(),
            'toname'   => $to->getName(),
            'from'     => $from->getEmail(),
            'fromname' => $from->getName(),
            'subject'  => $message->getSubject(),
            'text'     => $message->getText(),
            'html'     => $message->getHtml(),
            'bcc'      => $this->normalizeIdentity($message->getBcc())->getEmail(),
            'replyto'  => $message->getReplyTo(),
            'headers'  => json_encode($message->getHeaders()),
        );

        if ($message instanceof TaggableInterface) {
            $parameters['x-smtpapi']['category'] = (array) $message->getTag();
        }

        return http_build_query(array_filter($parameters));
    }
}
