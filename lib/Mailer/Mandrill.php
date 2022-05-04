<?php

namespace Stampie\Mailer;

use Psr\Http\Message\ResponseInterface;
use Stampie\Attachment;
use Stampie\Exception\ApiException;
use Stampie\Exception\HttpException;
use Stampie\Mailer;
use Stampie\Message\AttachmentsAwareInterface;
use Stampie\Message\MetadataAwareInterface;
use Stampie\Message\TaggableInterface;
use Stampie\MessageInterface;
use Stampie\Util\AttachmentUtils;

/**
 * Sends emails to Mandrill server.
 *
 * @author Christophe Coevoet <stof@notk.org>
 */
class Mandrill extends Mailer
{
    /**
     * @var string|null
     */
    private $subaccount;

    /**
     * @param string|null $subaccount
     *
     * @return void
     */
    public function setSubaccount($subaccount)
    {
        $this->subaccount = $subaccount;
    }

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
        return [
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function format(MessageInterface $message)
    {
        $headers = array_filter(array_merge(
            $message->getHeaders(),
            ['Reply-To' => $message->getReplyTo()]
        ));

        $from = $this->normalizeIdentity($message->getFrom());

        $to = [];
        foreach ($this->normalizeIdentities($message->getTo()) as $recipient) {
            $to[] = ['email' => $recipient->getEmail(), 'name' => $recipient->getName(), 'type' => 'to'];
        }

        foreach ($this->normalizeIdentities($message->getCc()) as $recipient) {
            $to[] = ['email' => $recipient->getEmail(), 'name' => $recipient->getName(), 'type' => 'cc'];
        }

        foreach ($this->normalizeIdentities($message->getBcc()) as $recipient) {
            $to[] = ['email' => $recipient->getEmail(), 'name' => $recipient->getName(), 'type' => 'bcc'];
        }

        $tags = [];
        if ($message instanceof TaggableInterface) {
            $tags = (array) $message->getTag();
        }

        $metadata = [];
        if ($message instanceof MetadataAwareInterface) {
            $metadata = array_filter($message->getMetadata());
        }

        $images = [];
        $attachments = [];
        if ($message instanceof AttachmentsAwareInterface) {
            list($attachments, $images) = $this->processAttachments($message->getAttachments());
        }

        $parameters = [
            'key' => $this->getServerToken(),
            'message' => array_filter([
                'from_email' => $from->getEmail(),
                'from_name' => $from->getName(),
                'to' => $to,
                'subject' => $message->getSubject(),
                'headers' => $headers,
                'text' => $message->getText(),
                'html' => $message->getHtml(),
                'tags' => $tags,
                'metadata' => $metadata,
                'attachments' => $attachments,
                'images' => $images,
            ]),
        ];

        if ($this->subaccount) {
            $parameters['message']['subaccount'] = $this->subaccount;
        }

        return json_encode($parameters, \JSON_THROW_ON_ERROR);
    }

    /**
     * {@inheritdoc}
     *
     * "You can consider any non-200 HTTP response code an error - the returned data will contain more detailed information"
     */
    protected function handle(ResponseInterface $response)
    {
        $httpException = new HttpException($response->getStatusCode(), $response->getReasonPhrase());
        $error = json_decode((string) $response->getBody());

        throw new ApiException($error->message, $httpException, $error->code);
    }

    /**
     * @param Attachment[] $attachments
     *
     * @return array{list<array{type: string, name: string, content: string}>, list<array{type: string, name: string, content: string}>}
     *     First element: Attachments – an array containing arrays of the following format
     *               array(
     *               'type' => type,
     *               'name' => name,
     *               'content' => base64-encoded content,
     *               )
     *
     *     Second element: Inline images – an array containing arrays of the following format
     *         array(
     *             'type' => type,
     *             'name' => id,
     *             'content' => base64-encoded content,
     *         )
     */
    protected function processAttachments(array $attachments)
    {
        $attachments = AttachmentUtils::processAttachments($attachments);

        $processedAttachments = [];
        $images = [];
        foreach ($attachments as $name => $attachment) {
            $type = $attachment->getType();
            $item = [
                'type' => $type,
                'name' => $name,
                'content' => base64_encode($this->getAttachmentContent($attachment)),
            ];

            $id = $attachment->getId();
            if (strpos($type, 'image/') === 0 && isset($id)) {
                // Inline image
                $item['name'] = $id;
                $images[] = $item;
            } else {
                // Attached
                $processedAttachments[] = $item;
            }
        }

        return [$processedAttachments, $images];
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
