<?php

namespace Stampie\Mailer;

use Stampie\Mailer;
use Stampie\Message\MetadataAwareInterface;
use Stampie\MessageInterface;
use Stampie\Message\TaggableInterface;
use Stampie\Message\AttachmentsInterface;
use Stampie\Adapter\ResponseInterface;
use Stampie\AttachmentInterface;
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
            $to[] = array('email' => $recipient->getEmail(), 'name' => $recipient->getName(), 'type' => 'to');
        }

        foreach ($this->normalizeIdentities($message->getCc()) as $recipient) {
            $to[] = array('email' => $recipient->getEmail(), 'name' => $recipient->getName(), 'type' => 'cc');
        }

        foreach ($this->normalizeIdentities($message->getBcc()) as $recipient) {
            $to[] = array('email' => $recipient->getEmail(), 'name' => $recipient->getName(), 'type' => 'bcc');
        }

        $tags = array();
        if ($message instanceof TaggableInterface) {
            $tags = (array) $message->getTag();
        }

        $metadata = array();
        if ($message instanceof MetadataAwareInterface) {
            $metadata = array_filter($message->getMetadata());
        }

        $self = $this; // PHP5.3 compatibility
        $images      = array();
        $attachments = array();
        if ($message instanceof AttachmentsInterface) {
            $attachments = $this->processAttachments($message->getAttachments(), function ($name, AttachmentInterface $attachment) use (&$images, $self) {
                $type = $attachment->getType();
                $item = array(
                    'type'    => $type,
                    'name'    => $name,
                    'content' => base64_encode($self->getAttachmentContent($attachment)),
                );

                $id = $attachment->getID();
                if (strpos($type, 'image/') === 0 && isset($id)) {
                    // Inline image
                    $item['name'] = $id;
                    $images[] = $item;

                    return null; // Do not add to attachments
                }

                return $item;
            });
        }

        $parameters = array(
            'key'     => $this->getServerToken(),
            'message' => array_filter(array(
                'from_email'  => $from->getEmail(),
                'from_name'   => $from->getName(),
                'to'          => $to,
                'subject'     => $message->getSubject(),
                'headers'     => $headers,
                'text'        => $message->getText(),
                'html'        => $message->getHtml(),
                'tags'        => $tags,
                'metadata'    => $metadata,
                'attachments' => $attachments,
                'images'      => $images,
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

    /**
     * @param AttachmentInterface $attachment
     * @return string
     */
    protected function getAttachmentContent(AttachmentInterface $attachment){
        return file_get_contents($attachment->getPath());
    }

    /**
     * {@inheritdoc}
     */
    protected function processAttachments(array $attachments, callable $callback)
    {
        // Strip keys
        return array_values(parent::processAttachments($attachments, $callback));
    }
}
