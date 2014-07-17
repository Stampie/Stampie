<?php

namespace Stampie\Mailer;

use Stampie\Mailer;
use Stampie\Message\MetadataAwareInterface;
use Stampie\MessageInterface;
use Stampie\Message\TaggableInterface;
use Stampie\Message\AttachmentsAwareInterface;
use Stampie\Adapter\ResponseInterface;
use Stampie\Attachment;
use Stampie\Exception\HttpException;

/**
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class MailGun extends Mailer
{
    /**
     * {@inheritdoc}
     */
    protected function getEndpoint()
    {
        list($domain,) = explode(':', $this->getServerToken());

        return 'https://api.mailgun.net/v2/' . $domain . '/messages';
    }

    /**
     * {@inheritdoc}
     * @throws \InvalidArgumentException
     */
    public function setServerToken($serverToken)
    {
        if (false === strpos($serverToken, ':')) {
            throw new \InvalidArgumentException('MailGun uses a "custom.domain.tld:key-hash" based ServerToken');
        }

        parent::setServerToken($serverToken);
    }

    /**
     * {@inheritdoc}
     */
    protected function getHeaders()
    {
        list(, $serverToken) = explode(':', $this->getServerToken());

        return array(
            'Authorization' => 'Basic ' . base64_encode('api:' . $serverToken),
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getFiles(MessageInterface $message)
    {
        if (!($message instanceof AttachmentsAwareInterface)) {
            return array();
        }

        // Process files
        list($attachments, $inline) = $this->processAttachments($message->getAttachments());

        // Format params
        $files = array();
        if ($attachments) {
            $files['attachment'] = $attachments;
        }
        if ($inline) {
            $files['inline'] = $inline;
        }
        return $files;
    }

    /**
     * {@inheritdoc}
     */
    protected function format(MessageInterface $message)
    {
        // Custom headers should be prefixed with h:X-My-Header
        $headers = array_merge(
            $message->getHeaders(),
            array('Reply-To' => $message->getReplyTo())
        );
        array_walk($headers, function (&$value, &$key) {
            $key = 'h:' . $key;
        });

        $parameters = array(
            'from'    => $this->buildIdentityString($message->getFrom()),
            'to'      => $this->buildIdentityString($message->getTo()),
            'subject' => $message->getSubject(),
            'text'    => $message->getText(),
            'html'    => $message->getHtml(),
            'cc'      => $this->buildIdentityString($message->getCc()),
            'bcc'     => $this->buildIdentityString($message->getBcc()),
        );

        if ($message instanceof TaggableInterface) {
            $parameters['o:tag'] = (array) $message->getTag();
        }

        $metadata = array();
        if ($message instanceof MetadataAwareInterface) {
            $metadata = array_filter($message->getMetadata());
            // Custom variables should be prefixed with v:my_var
            array_walk($metadata, function (&$value, &$key) {
                $key = 'v:' . $key;
            });
        }

        return http_build_query(array_filter(array_merge($headers, $parameters, $metadata)));
    }

    /**
     * {@inheritdoc}
     */
    protected function handle(ResponseInterface $response)
    {
        throw new HttpException($response->getStatusCode(), $response->getStatusText());
    }

    /**
     * @param Attachment[] $attachments
     * @return array    First element: An array of attachment paths. Second element: An array of inline paths
     */
    protected function processAttachments(array $attachments)
    {
        $processedAttachments = array();
        $inline = array();
        foreach ($attachments as $attachment) {
            $path = $attachment->getPath();
            $id   = $attachment->getId();
            if (isset($id)) {
                // Inline
                $inline[] = $path;
            } else {
                // Attached
                $processedAttachments[] = $path;
            }
        }

        return array($processedAttachments, $inline);
    }
}
