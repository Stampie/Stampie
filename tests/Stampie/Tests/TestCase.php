<?php

namespace Stampie\Tests;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Stampie\Attachment;
use Stampie\MessageInterface;
use Stampie\Tests\Mailer\AttachmentMessage;
use Stampie\Tests\Mailer\MetadataAwareMessage;
use Stampie\Tests\Mailer\TaggableMessage;

class TestCase extends BaseTestCase
{
    /**
     * @return MockObject&MessageInterface
     */
    protected function getMessageMock($from, $to, $subject, $html = null, $text = null, array $headers = [])
    {
        $message = $this->getMockBuilder(MessageInterface::class)->getMock();

        $this->configureMessageMock($message, $from, $to, $subject, $html, $text, $headers);

        return $message;
    }

    /**
     * @return MockObject&MessageInterface
     */
    protected function getTaggableMessageMock($from, $to, $subject, $html = null, $text = null, array $headers = [], $tags = [])
    {
        $message = $this->getMockBuilder(TaggableMessage::class)->getMock();

        $this->configureMessageMock($message, $from, $to, $subject, $html, $text, $headers);

        $message
            ->expects($this->any())
            ->method('getTag')
            ->will($this->returnValue($tags));

        return $message;
    }

    /**
     * @return MockObject&MessageInterface
     */
    protected function getMetadataAwareMessageMock($from, $to, $subject, $html = null, $text = null, array $headers = [], array $metadata = [])
    {
        $message = $this->getMockBuilder(MetadataAwareMessage::class)->getMock();

        $this->configureMessageMock($message, $from, $to, $subject, $html, $text, $headers);

        $message
            ->expects($this->any())
            ->method('getMetadata')
            ->will($this->returnValue($metadata));

        return $message;
    }

    /**
     * @return MockObject&MessageInterface
     */
    protected function getCarbonCopyMock($from, $to, $subject, $html = null, $text = null, array $headers = [], $cc = null)
    {
        $message = $this->getMockBuilder(MessageInterface::class)->getMock();

        $this->configureMessageMock($message, $from, $to, $subject, $html, $text, $headers);

        $message
            ->expects($this->any())
            ->method('getCc')
            ->will($this->returnValue($cc));

        return $message;
    }

    /**
     * @return MockObject&MessageInterface
     */
    protected function getBlindCarbonCopyMock($from, $to, $subject, $html = null, $text = null, array $headers = [], $bcc = null)
    {
        $message = $this->getMockBuilder(MessageInterface::class)->getMock();

        $this->configureMessageMock($message, $from, $to, $subject, $html, $text, $headers);

        $message
            ->expects($this->any())
            ->method('getBcc')
            ->will($this->returnValue($bcc));

        return $message;
    }

    /**
     * @return MockObject&MessageInterface
     */
    public function getAttachmentsMessageMock($from, $to, $subject, $html = null, $text = null, array $headers = [], array $attachments = [])
    {
        $message = $this->getMockBuilder(AttachmentMessage::class)->getMock();

        $this->configureMessageMock($message, $from, $to, $subject, $html, $text, $headers);

        $message
            ->expects($this->any())
            ->method('getAttachments')
            ->will($this->returnValue($attachments));

        return $message;
    }

    protected function getAttachmentMock($path, $name, $type, $id = null)
    {
        $attachment = $this->getMockBuilder(Attachment::class)
            ->disableOriginalConstructor()
            ->getMock();

        $attachment
            ->expects($this->any())
            ->method('getPath')
            ->will($this->returnValue(__DIR__.'/../../Fixtures/'.$path));
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

    private function configureMessageMock(MockObject $message, $from, $to, $subject, $html = null, $text = null, array $headers = [])
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
