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
use Stampie\Util\AttachmentUtils;

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

        $images      = array();
        $attachments = array();
        if ($message instanceof AttachmentsInterface) {
            list($attachments, $images) = $this->processAttachments($message->getAttachments());
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
     * @param AttachmentInterface[] $attachments
     * @return array
     *     First element: Attachments – an array containing arrays of the following format
     *         array(
     *             'type'    => type,
     *             'name'    => name,
     *             'content' => base64-encoded content,
     *         )
     *
     *     Second element: Inline images – an array containing arrays of the following format
     *         array(
     *             'type'    => type,
     *             'name'    => id,
     *             'content' => base64-encoded content,
     *         )
     */
    protected function processAttachments(array $attachments)
    {
        $attachments = AttachmentUtils::processAttachments($attachments);

        $processedAttachments = array();
        $images = array();
        foreach ($attachments as $name => $attachment) {
            $type = $attachment->getType();
            $item = array(
                'type'    => $type,
                'name'    => $name,
                'content' => base64_encode($this->getAttachmentContent($attachment)),
            );

            $id = $attachment->getID();
            if (strpos($type, 'image/') === 0 && isset($id)) {
                // Inline image
                $item['name'] = $id;
                $images[] = $item;
            } else {
                // Attached
                $processedAttachments[] = $item;
            }
        }

        return array($processedAttachments, $images);
    }

    /**
     * @param AttachmentInterface $attachment
     * @return string
     */
    protected function getAttachmentContent(AttachmentInterface $attachment)
    {
        return file_get_contents($attachment->getPath());
    }
}
