<?php

namespace Stampie\Mailer;

use Stampie\Mailer;
use Stampie\MessageInterface;
use Stampie\Adapter\AdapterInterface;

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
     * @param MessageInterface $message
     * @throws \LogicException
     * @return Boolean
     */
    public function send(MessageInterface $message)
    {
        $response = $this->adapter->send($this->getEndpoint(), $this->format($message), array(
            'Content-Type' => 'application/json',
            'X-Postmark-Server-Token' => $this->getServerToken(),
        ));

        // We are all clear if status is HTTP 200 OK
        if ($response->getStatusCode() === 200) {
            return true;
        }

        // 401 Unauthorized 
        // 500 Internal Server Error
        if (in_array($statusCode = $response->getStatusCode(), array(401, 500))) {
            throw new \LogicException($statusCode == 401 ? 'Unauthorized' : 'Internal Server Error', $statusCode);
        }

        // The error is returned in JSON with {ErrorCode : 405, Message: "Details"}
        $error = json_decode($response->getContent());

        // 422 Unprocessable Entity
        throw new \LogicException($error->Message, $error->ErrorCode);
    }

    /**
     * @param MessageInterface $message
     * @return string
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

        if (empty($parameters['HtmlBody']) && empty($parameters['TextBody'])) {
            throw new \InvalidArgumentException('You cannot send empty emails');
        }

        return json_encode($parameters);
    }
}
