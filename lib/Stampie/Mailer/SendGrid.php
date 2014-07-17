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
use Stampie\Exception\ApiException;
use Stampie\Util\AttachmentUtils;

/**
 * Mailer to be used with SendGrid Web API
 *
 * @author Henrik Bjrnskov <henrik@bjrnskov.dk>
 */
class SendGrid extends Mailer
{
    /**
     * {@inheritdoc}
     */
    protected function getEndpoint()
    {
        return 'https://sendgrid.com/api/mail.send.json';
    }

    /**
     * {@inheritdoc}
     * @throws \InvalidArgumentException
     */
    public function setServerToken($serverToken)
    {
        if (false === strpos( $serverToken, ':')) {
            throw new \InvalidArgumentException('SendGrid uses a "username:password" based ServerToken');
        }

        parent::setServerToken($serverToken);
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
        list($attachments,) = $this->processAttachments($message->getAttachments());

        // Format params
        $files = array();
        if ($attachments) {
            $files['files'] = $attachments;
        }
        return $files;
    }

    /**
     * {@inheritdoc}
     */
    protected function handle(ResponseInterface $response)
    {
        $httpException = new HttpException($response->getStatusCode(), $response->getStatusText());

        // 4xx will containt error information in the body encoded as JSON
        if (!in_array($response->getStatusCode(), range(400, 417))) {
            throw $httpException;
        }

        $error = json_decode($response->getContent());
        throw new ApiException(implode(', ', (array) $error->errors), $httpException);
    }

    /**
     * {@inheritdoc}
     */
    protected function format(MessageInterface $message)
    {
        // We should split up the ServerToken on : to get username and password
        list($username, $password) = explode(':', $this->getServerToken());

        $from = $this->normalizeIdentity($message->getFrom());

        $toEmails = array();
        $toNames = array();

        foreach ($this->normalizeIdentities($message->getTo()) as $recipient) {
            $toEmails[] = $recipient->getEmail();
            $toNames[] = $recipient->getName();
        }

        $bccEmails = array();

        foreach ($this->normalizeIdentities($message->getBcc()) as $recipient) {
            $bccEmails[] = $recipient->getEmail();
        }

        $smtpApi = array();

        if ($message instanceof TaggableInterface) {
            $smtpApi['category'] = (array) $message->getTag();
        }

        if ($message instanceof MetadataAwareInterface) {
            $smtpApi['unique_args'] = array_filter($message->getMetadata());
        }

        $inline = array();
        if ($message instanceof AttachmentsAwareInterface) {
            // Store inline attachment references
            list(,$inline) = $this->processAttachments($message->getAttachments());
        }

        $parameters = array(
            'api_user' => $username,
            'api_key'  => $password,
            'to'       => $toEmails,
            'toname'   => $toNames,
            'from'     => $from->getEmail(),
            'fromname' => $from->getName(),
            'subject'  => $message->getSubject(),
            'text'     => $message->getText(),
            'html'     => $message->getHtml(),
            'bcc'      => $bccEmails,
            'replyto'  => $message->getReplyTo(),
            'headers'  => json_encode($message->getHeaders()),
            'content'  => $inline,
        );

        if ($smtpApi) {
            $parameters['x-smtpapi'] = json_encode(array_filter($smtpApi));
        }

        return http_build_query(array_filter($parameters));
    }

    /**
     * @param Attachment[] $attachments
     * @return array First element: All attachments – array(name => path). Second element: Inline attachments – array(id => name)
     */
    protected function processAttachments(array $attachments)
    {
        $attachments = AttachmentUtils::processAttachments($attachments);

        $processedAttachments = array();
        $inline = array();
        foreach ($attachments as $name => $attachment) {
            $id = $attachment->getId();
            if (isset($id)) {
                // Reference inline
                $inline[$id] = $name;
            }

            $processedAttachments[$name] = $attachment->getPath();
        }

        return array($processedAttachments, $inline);
    }
}
