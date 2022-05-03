<?php

namespace Stampie\Mailer;

use Psr\Http\Message\ResponseInterface;
use Stampie\Attachment;
use Stampie\Exception\ApiException;
use Stampie\Exception\HttpException;
use Stampie\IdentityInterface;
use Stampie\Mailer;
use Stampie\Message\AttachmentsAwareInterface;
use Stampie\Message\MetadataAwareInterface;
use Stampie\Message\TaggableInterface;
use Stampie\MessageInterface;
use Stampie\Util\AttachmentUtils;

/**
 * @author Jérôme Parmentier <jerome@prmntr.me>
 */
class Mailjet extends Mailer
{
    /**
     * {@inheritdoc}
     */
    protected function getEndpoint()
    {
        return 'https://api.mailjet.com/v3.1/send';
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     */
    public function setServerToken($serverToken)
    {
        if (false === strpos($serverToken, ':')) {
            throw new \InvalidArgumentException('Mailjet uses a "publicApiKey:privateApiKey" based ServerToken');
        }

        parent::setServerToken($serverToken);
    }

    /**
     * {@inheritdoc}
     */
    protected function getHeaders()
    {
        return [
            'Accept' => 'application/json',
            'Authorization' => sprintf('Basic %s', base64_encode($this->getServerToken())),
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function format(MessageInterface $message)
    {
        $parameters = [
            'From' => $this->buildSenderField($message->getFrom()),
            'To' => $this->buildRecipientsField($message->getTo()),
            'Cc' => $this->buildRecipientsField($message->getCc()),
            'Bcc' => $this->buildRecipientsField($message->getBcc()),
            'Subject' => $message->getSubject(),
            'Headers' => $message->getHeaders(),
            'HTMLPart' => $message->getHtml(),
            'TextPart' => $message->getText(),
            'ReplyTo' => $this->buildSenderField($message->getReplyTo()),
        ];

        $attachments = $this->processAttachments($message);
        if ($attachments) {
            if ($attachments['attached']) {
                $parameters['Attachments'] = $attachments['attached'];
            }

            if ($attachments['inlined']) {
                $parameters['InlinedAttachments'] = $attachments['inlined'];
            }
        }

        if ($message instanceof MetadataAwareInterface) {
            $parameters['EventPayload'] = $message->getMetadata();
        }

        if ($message instanceof TaggableInterface) {
            $parameters['MonitoringCategory'] = $message->getTag();
        }

        return json_encode(['Messages' => [array_filter($parameters)]]);
    }

    /**
     * {@inheritdoc}
     */
    protected function handle(ResponseInterface $response)
    {
        $statusCode = $response->getStatusCode();
        $httpException = new HttpException($statusCode, $response->getReasonPhrase());

        if (!in_array($statusCode, [400, 401, 403], true)) {
            throw $httpException;
        }

        $error = json_decode((string) $response->getBody(), true);

        if (isset($error['ErrorMessage'])) {
            throw new ApiException($error['ErrorMessage'], $httpException);
        }

        $errorMessages = [];
        foreach ($error['Messages'] as $message) {
            if ('error' !== $message['Status']) {
                continue;
            }

            foreach ($message['Errors'] as $mailError) {
                $errorMessages[] = $mailError['ErrorMessage'];
            }
        }

        throw new ApiException(implode(', ', $errorMessages), $httpException);
    }

    /**
     * @param IdentityInterface|string|null $identity
     *
     * @return array{Email?: string, Name?: string}
     */
    protected function buildSenderField($identity)
    {
        if (null === $identity) {
            return [];
        }

        if (is_string($identity)) {
            return [
                'Email' => $identity,
            ];
        }

        $sender = [
            'Email' => $identity->getEmail(),
        ];

        if (null !== $name = $identity->getName()) {
            $sender['Name'] = $name;
        }

        return $sender;
    }

    /**
     * @param array<IdentityInterface|string>|IdentityInterface|string|null $identities
     *
     * @return list<array{Email: string, Name?: string}>
     */
    protected function buildRecipientsField($identities)
    {
        if (null === $identities) {
            return [];
        }

        if (is_string($identities)) {
            return [
                [
                    'Email' => $identities,
                ],
            ];
        }

        $identities = (array) $identities;

        $recipients = [];
        foreach ($identities as $identity) {
            if (is_string($identity)) {
                $recipients[] = [
                    'Email' => $identity,
                ];

                continue;
            }

            $recipient = [
                'Email' => $identity->getEmail(),
            ];

            if (null !== $name = $identity->getName()) {
                $recipient['Name'] = $name;
            }

            $recipients[] = $recipient;
        }

        return $recipients;
    }

    /**
     * @param MessageInterface $message
     *
     * @return array{attached?: list<array{Filename: string, Base64Content: string, ContentType: string}>, inlined?: list<array{Filename: string, Base64Content: string, ContentType: string, ContentID: string}>}
     */
    protected function processAttachments(MessageInterface $message)
    {
        if (!$message instanceof AttachmentsAwareInterface) {
            return [];
        }

        $processedAttachments = [
            'attached' => [],
            'inlined' => [],
        ];

        $attachments = AttachmentUtils::processAttachments($message->getAttachments());
        foreach ($attachments as $name => $attachment) {
            $item = [
                'Filename' => $name,
                'Base64Content' => base64_encode($this->getAttachmentContent($attachment)),
                'ContentType' => $attachment->getType(),
            ];

            if (null !== $id = $attachment->getId()) {
                $item['ContentID'] = $id;
                $processedAttachments['inlined'][] = $item;

                continue;
            }

            $processedAttachments['attached'][] = $item;
        }

        return $processedAttachments;
    }

    /**
     * @param Attachment $attachment
     *
     * @return string
     */
    protected function getAttachmentContent(Attachment $attachment)
    {
        return file_get_contents($attachment->getPath());
    }
}
