<?php

namespace Stampie\Mailer;

use Stampie\Mailer;
use Stampie\Message\TaggableInterface;
use Stampie\Message\AttachmentsInterface;
use Stampie\MessageInterface;
use Stampie\Adapter\ResponseInterface;
use Stampie\AttachmentInterface;
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
        $headers = array();
        foreach ($message->getHeaders() as $name => $value) {
            $headers[] = array('Name' => $name, 'Value' => $value);
        }

        $parameters = array(
            'From'     => $this->buildIdentityString($message->getFrom()),
            'To'       => $this->buildIdentityString($message->getTo()),
            'Subject'  => $message->getSubject(),
            'Headers'  => $headers,
            'HtmlBody' => $message->getHtml(),
            'TextBody' => $message->getText(),
            'ReplyTo'  => $message->getReplyTo(),
        );

        if ($message instanceof TaggableInterface) {
            $tag = $message->getTag();

            if (is_array($tag)) {
                $tag = reset($tag);
            }

            $parameters['Tag'] = $tag ;
        }

        if ($message instanceof AttachmentsInterface) {
            $self = $this; // PHP5.3 compatibility
            $attachments = $this->processAttachments($message->getAttachments(), function ($name, AttachmentInterface $attachment) use ($self) {
                $item = array(
                    'Name'        => $name,
                    'Content'     => base64_encode($self->getAttachmentContent($attachment)),
                    'ContentType' => $attachment->getType(),
                );

                $id = $attachment->getID();
                if (isset($id)) {
                    $item['ContentID']    = $id;
                }

                return $item;
            });

            if ($attachments) {
                $parameters['Attachments']    = $attachments;
            }
        }

        return json_encode(array_filter($parameters));
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
    protected function processAttachments(array $attachments, $callback){
        // Strip keys
        return array_values(parent::processAttachments($attachments, $callback));
    }
}
