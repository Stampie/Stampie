<?php

namespace Stampie\Mailer;

use Stampie\Mailer;
use Stampie\Message\MetadataAwareInterface;
use Stampie\MessageInterface;
use Stampie\Message\TaggableInterface;
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

        $from = $this->normalizeIdentity($message->getFrom());

        $to = array();
        foreach ($this->normalizeIdentities($message->getTo()) as $recipient) {
            $to[] = array('email' => $recipient->getEmail(), 'name' => $recipient->getName());
        }

        $tags = array();
        if ($message instanceof TaggableInterface) {
            $tags = (array) $message->getTag();
        }

        $metadata = array();
        if ($message instanceof MetadataAwareInterface) {
            $metadata = array_filter($message->getMetadata());
        }

        $parameters = array(
            'key'     => $this->getServerToken(),
            'message' => array_filter(array(
                'from_email' => $from->getEmail(),
                'from_name'  => $from->getName(),
                'to'         => $to,
                'subject'    => $message->getSubject(),
                'headers'    => $headers,
                'text'       => $message->getText(),
                'html'       => $message->getHtml(),
                'tags'       => $tags,
                'metadata'   => $metadata,
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
