<?php

namespace Stampie\Mailer;

use Http\Client\HttpClient;
use Psr\Http\Message\ResponseInterface;
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
    /**
     * @var bool
     */
    private $transactional;

    /**
     * @param HttpClient $httpClient
     * @param string     $serverToken
     * @param bool       $transactional Whether messages are transactional for unsubscribe and suppression purposes
     *
     * @see https://en.wikipedia.org/wiki/Email_marketing#Transactional_emails
     */
    public function __construct(HttpClient $httpClient, $serverToken, $transactional = true)
    {
        parent::__construct($httpClient, $serverToken);
        $this->transactional = (bool) $transactional;
    }

    /**
     * {@inheritdoc}
     */
    protected function getEndpoint()
    {
        return 'https://api.sparkpost.com/api/v1/transmissions';
    }

    /**
     * {@inheritdoc}
     */
    protected function getHeaders()
    {
        return [
            'Content-Type' => 'application/json',
            'Authorization' => $this->getServerToken(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function format(MessageInterface $message)
    {
        $from = $this->normalizeIdentity($message->getFrom());

        $parameters = [
            'options' => [
                'transactional' => $this->transactional,
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

        $merged = array_merge($normalizedTo, $normalizedCc, $normalizedBcc);
        foreach ($merged as $recipient) {
            /*
             * The reason we are specifying header_to is so that each recipient can see *everybody* who received
             * the email. Without it, each recipient will only see their email in the To field.
             *
             * IMPORTANT: Do not specify address.name!
             * If given, SparkPost will try to wrap the entire header_to value, mangling it in the process.
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
            $parameters['content']['headers']['CC'] = IdentityUtils::buildIdentityString($normalizedCc);
        }

        return json_encode($parameters);
    }

    /**
     * {@inheritdoc}
     *
     * HTTP codes 400-429 will throw an ApiException, otherwise an HttpException is thrown.
     */
    protected function handle(ResponseInterface $response)
    {
        $httpException = new HttpException($response->getStatusCode(), $response->getReasonPhrase());

        // 400-429 will contain error information in the body encoded as JSON
        if ($response->getStatusCode() >= 400 && $response->getStatusCode() <= 429) {
            throw new ApiException((string) $response->getBody(), $httpException);
        }

        throw $httpException;
    }

    /**
     * @return string
     */
    private function getAttachmentContent(Attachment $attachment)
    {
        return file_get_contents($attachment->getPath());
    }
}
