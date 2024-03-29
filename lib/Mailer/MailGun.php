<?php

namespace Stampie\Mailer;

use Psr\Http\Message\ResponseInterface;
use Stampie\Attachment;
use Stampie\Exception\HttpException;
use Stampie\Mailer;
use Stampie\Message\AttachmentsAwareInterface;
use Stampie\Message\MetadataAwareInterface;
use Stampie\Message\TaggableInterface;
use Stampie\MessageInterface;

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
        list($domain) = explode(':', $this->getServerToken());

        return 'https://api.mailgun.net/v2/'.$domain.'/messages';
    }

    /**
     * {@inheritdoc}
     *
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

        return [
            'Authorization' => 'Basic '.base64_encode('api:'.$serverToken),
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
    }

    protected function getFiles(MessageInterface $message)
    {
        if (!($message instanceof AttachmentsAwareInterface)) {
            return [];
        }

        // Process files
        list($attachments, $inline) = $this->processAttachments($message->getAttachments());

        // Format params
        $files = [];
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
        $allHeaders = array_merge(
            $message->getHeaders(),
            ['Reply-To' => $message->getReplyTo()]
        );
        $headers = [];
        foreach (array_filter($allHeaders) as $key => $value) {
            // Custom headers should be prefixed with h:X-My-Header
            $headers['h:'.$key] = $value;
        }

        $parameters = [
            'from' => $this->buildIdentityString($message->getFrom()),
            'to' => $this->buildIdentityString($message->getTo()),
            'subject' => $message->getSubject(),
            'text' => $message->getText(),
            'html' => $message->getHtml(),
            'cc' => $this->buildIdentityString($message->getCc()),
            'bcc' => $this->buildIdentityString($message->getBcc()),
        ];

        if ($message instanceof TaggableInterface) {
            $parameters['o:tag'] = (array) $message->getTag();
        }

        $metadata = [];
        if ($message instanceof MetadataAwareInterface) {
            foreach (array_filter($message->getMetadata()) as $key => $value) {
                // Custom variables should be prefixed with v:my_var
                $metadata['v:'.$key] = $value;
            }
        }

        return http_build_query(array_filter(array_merge($headers, $parameters, $metadata)));
    }

    /**
     * {@inheritdoc}
     */
    protected function handle(ResponseInterface $response)
    {
        throw new HttpException($response->getStatusCode(), $response->getReasonPhrase());
    }

    /**
     * @param Attachment[] $attachments
     *
     * @return array{array<string, string>, string[]} First element: An array of attachment paths. Second element: An array of inline paths
     */
    protected function processAttachments(array $attachments)
    {
        $processedAttachments = [];
        $inline = [];
        foreach ($attachments as $attachment) {
            $path = $attachment->getPath();
            $id = $attachment->getId();
            if (isset($id)) {
                // Inline
                $inline[] = $path;
            } else {
                // Attached
                $processedAttachments[$attachment->getName()] = $path;
            }
        }

        return [$processedAttachments, $inline];
    }
}
