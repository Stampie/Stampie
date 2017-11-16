<?php

namespace Stampie\Tests;

use PHPUnit\Framework\TestCase;

abstract class BaseMailerTest extends TestCase
{
    protected $adapter;

    /**
     * @var \Stampie\MailerInterface
     */
    protected $mailer;

    public function setUp()
    {
        $this->adapter = $this->getMockBuilder('Http\Client\HttpClient')->getMock();
    }

    protected function getResponseMock($statusCode, array $content)
    {
        $response = $this->getMockBuilder('Stampie\Adapter\ResponseInterface')->getMock();
        $response
            ->expects($this->any())
            ->method('getStatusCode')
            ->will($this->returnValue($statusCode));

        $response
            ->expects($this->any())
            ->method('getContent')
            ->will($this->returnValue(json_encode($content)));

        return $response;
    }

    protected function getMessageMock($from, $to, $subject, $html = null, $text = null, array $headers = [])
    {
        $message = $this->getMockBuilder('Stampie\MessageInterface')->getMock();

        $this->configureMessageMock($message, $from, $to, $subject, $html, $text, $headers);

        return $message;
    }

    protected function getTaggableMessageMock($from, $to, $subject, $html = null, $text = null, array $headers = [], $tags = [])
    {
        $message = $this->getMockBuilder('Stampie\Tests\Mailer\TaggableMessage')->getMock();

        $this->configureMessageMock($message, $from, $to, $subject, $html, $text, $headers);

        $message
            ->expects($this->any())
            ->method('getTag')
            ->will($this->returnValue($tags));

        return $message;
    }

    protected function getMetadataAwareMessageMock($from, $to, $subject, $html = null, $text = null, array $headers = [], array $metadata = [])
    {
        $message = $this->getMockBuilder('Stampie\Tests\Mailer\MetadataAwareMessage')->getMock();

        $this->configureMessageMock($message, $from, $to, $subject, $html, $text, $headers);

        $message
            ->expects($this->any())
            ->method('getMetadata')
            ->will($this->returnValue($metadata));

        return $message;
    }

    protected function getCarbonCopyMock($from, $to, $subject, $html = null, $text = null, array $headers = [], $cc = null)
    {
        $message = $this->getMockBuilder('Stampie\MessageInterface')->getMock();

        $this->configureMessageMock($message, $from, $to, $subject, $html, $text, $headers);

        $message
            ->expects($this->any())
            ->method('getCc')
            ->will($this->returnValue($cc));

        return $message;
    }

    protected function getBlindCarbonCopyMock($from, $to, $subject, $html = null, $text = null, array $headers = [], $bcc = null)
    {
        $message = $this->getMockBuilder('Stampie\MessageInterface')->getMock();

        $this->configureMessageMock($message, $from, $to, $subject, $html, $text, $headers);

        $message
            ->expects($this->any())
            ->method('getBcc')
            ->will($this->returnValue($bcc));

        return $message;
    }

    public function getAttachmentsMessageMock($from, $to, $subject, $html = null, $text = null, array $headers = [], array $attachments = [])
    {
        $message = $this->getMockBuilder('Stampie\\Tests\\Mailer\\AttachmentMessage')->getMock();

        $this->configureMessageMock($message, $from, $to, $subject, $html, $text, $headers);

        $message
            ->expects($this->any())
            ->method('getAttachments')
            ->will($this->returnValue($attachments));

        return $message;
    }

    protected function getAttachmentMock($path, $name, $type, $id = null)
    {
        $attachment = $this->getMockBuilder('\\Stampie\\Attachment')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $attachment
            ->expects($this->any())
            ->method('getPath')
            ->will($this->returnValue($path));
        $attachment
            ->expects($this->any())
            ->method('getName')
            ->will($this->returnValue($name));
        $attachment
            ->expects($this->any())
            ->method('getType')
            ->will($this->returnValue($type));
        $attachment
            ->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($id));

        /* @var \Stampie\Attachment $attachment */
        return $attachment;
    }

    private function configureMessageMock(\PHPUnit_Framework_MockObject_MockObject $message, $from, $to, $subject, $html = null, $text = null, array $headers = [])
    {
        $message
            ->expects($this->any())
            ->method('getFrom')
            ->will($this->returnValue($from));

        $message
            ->expects($this->any())
            ->method('getTo')
            ->will($this->returnValue($to));

        $message
            ->expects($this->any())
            ->method('getSubject')
            ->will($this->returnValue($subject));

        $message
            ->expects($this->any())
            ->method('getHtml')
            ->will($this->returnValue($html));

        $message
            ->expects($this->any())
            ->method('getText')
            ->will($this->returnValue($text));

        $message
            ->expects($this->any())
            ->method('getHeaders')
            ->will($this->returnValue($headers));
    }
}
