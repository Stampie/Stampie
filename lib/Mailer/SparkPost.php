<?php

namespace Stampie\Mailer;

use Stampie\Adapter\ResponseInterface;
use Stampie\Attachment;
use Stampie\Exception\ApiException;
use Stampie\Exception\HttpException;
use Stampie\Mailer;
use Stampie\Message\AttachmentsAwareInterface;
use Stampie\Message\MetadataAwareInterface;
use Stampie\Message\TaggableInterface;
use Stampie\MessageInterface;
use Stampie\Util\IdentityUtils;

/**
 * Mailer to be used with SparkPost's API.
 *
 * @author Shaun Simmons <shaun@envysphere.com>
 */
class SparkPost extends Mailer
{
    protected function getEndpoint()
    {
        return 'https://api.sparkpost.com/api/v1/transmissions';
    }

    protected function getHeaders()
    {
        return [
            'Authorization' => $this->getServerToken(),
        ];
    }

    protected function format(MessageInterface $message)
    {
        $from = $this->normalizeIdentity($message->getFrom());

        $parameters = [
            'options' => [
                'transactional' => true,
            ],
            'content' => [
                'from' => [
                    'name' => $from->getName(),
                    'email' => $from->getEmail(),
                ],
                'subject' => $message->getSubject(),
                'text' => $message->getText(),
                'html' => $message->getHtml(),
            ],
        ];

        if ($message->getReplyTo()) {
            $parameters['content']['reply_to'] = $message->getReplyTo();
        }

        if (count($message->getHeaders())) {
            $parameters['content']['headers'] = $message->getHeaders();
        }

        if ($message instanceof AttachmentsAwareInterface) {
            foreach ($message->getAttachments() as $attachment) {
                $inline = $attachment->getId() !== null;
                $parameters['content'][$inline ? 'inline_images' : 'attachments'][] = [
                    'type' => $attachment->getType(),
                    'name' => $inline ? $attachment->getId() : $attachment->getName(),
                    'data' => base64_encode($this->getAttachmentContent($attachment)),
                ];
            }
        }

        if ($message instanceof MetadataAwareInterface) {
            $metadata = array_filter($message->getMetadata());
            if (count($metadata)) {
                $parameters['metadata'] = $metadata;
            }
        }

        $tags = [];
        if ($message instanceof TaggableInterface) {
            $tags = (array) $message->getTag();
        }

        $normalizedTo = $this->normalizeIdentities($message->getTo());
        $normalizedCc = $this->normalizeIdentities($message->getCc());
        $normalizedBcc = $this->normalizeIdentities($message->getBcc());
        $toIdentityString = IdentityUtils::buildIdentityString($normalizedTo);

        /** @var \Stampie\Identity[] $merged */
        $merged = array_merge($normalizedTo, $normalizedCc, $normalizedBcc);
        foreach ($merged as $recipient) {
            /*
             * IMPORTANT:
             * Do not set address.name!
             * SparkPost will wrap the entire header_to value, e.g. Bob <bob@example.com, john@example.com>
             */

            $parameters['recipients'][] = [
                'address' => [
                    'email' => $recipient->getEmail(),
                    'header_to' => $toIdentityString,
                ],
                'tags' => $tags,
            ];
        }

        if (count($normalizedCc)) {
            $parameters['content']['headers']['Cc'] = IdentityUtils::buildIdentityString($normalizedCc);
        }

        return json_encode($parameters);
    }

    protected function handle(ResponseInterface $response)
    {
        $httpException = new HttpException($response->getStatusCode(), $response->getStatusText());

        // 4xx will contain error information in the body encoded as JSON
        if ($response->getStatusCode() < 400 || $response->getStatusCode() > 429) {
            throw $httpException;
        }

        throw new ApiException($response->getContent(), $httpException);
    }

    private function getAttachmentContent(Attachment $attachment)
    {
        return file_get_contents($attachment->getPath());
    }
}
