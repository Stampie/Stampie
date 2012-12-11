<?php

namespace Stampie\Mailer;

use Stampie\Mailer;
use Stampie\MessageInterface;
use Stampie\Adapter\ResponseInterface;
use Stampie\Exception\HttpException;
use Stampie\Exception\ApiException;

/**
 * Sends emails to Mandrill server
 *
 * @author Christophe Coevoet <stof@notk.org>
 */
class Mandrill extends Mailer
{
    /**
     * {@inheritdoc}
     */
    protected function getEndpoint()
    {
        return 'https://mandrillapp.com/api/1.0/messages/send.json';
    }

    /**
     * {@inheritdoc}
     */
    protected function getHeaders()
    {
        return array(
            'Content-Type' => 'application/json',
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function format(MessageInterface $message)
    {
        $headers = array_filter(array_merge(
            $message->getHeaders(),
            array('Reply-To' => $message->getReplyTo())
        ));

        $to = array(array('email' => $message->getTo()));
    
        if ($message->getCc()) {
            $cc = explode(',', $message->getCc());
            foreach ($cc as $email) {
                $to[] = array('email' => $email);
            }
        }

        $parameters = array(
            'key'     => $this->getServerToken(),
            'message' => array_filter(array(
                'from_email'  => $message->getFrom(),
                'to'          => $to,
                'subject'     => $message->getSubject(),
                'headers'     => $headers,
                'bcc_address' => $message->getBcc(),
                'text'        => $message->getText(),
                'html'        => $message->getHtml(),
            )),
        );

        return json_encode($parameters);
    }

    /**
     * {@inheritdoc}
     *
     * "You can consider any non-200 HTTP response code an error - the returned data will contain more detailed information"
     */
    protected function handle(ResponseInterface $response)
    {
        $httpException = new HttpException($response->getStatusCode(), $response->getStatusText());
        $error         = json_decode($response->getContent());

        throw new ApiException($error->message, $httpException, $error->code);
    }
}
