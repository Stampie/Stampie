<?php

namespace Stampie\Tests\Mailer;

use Stampie\Adapter\ResponseInterface;
use Stampie\Mailer\SparkPost;
use Stampie\MessageInterface;
use Stampie\Tests\BaseMailerTest;

class SparkPostTest extends BaseMailerTest
{
    const SERVER_TOKEN = 'abc123';

    public function setUp()
    {
        parent::setUp();
        $this->mailer = new TestSparkPost($this->adapter, self::SERVER_TOKEN);
    }

    public function testServerToken()
    {
        $this->assertEquals(self::SERVER_TOKEN, $this->mailer->getServerToken());
    }

    public function testEndpoint()
    {
        $this->assertEquals('https://api.sparkpost.com/api/v1/transmissions', $this->mailer->getEndpoint());
    }

    public function testHeaders()
    {
        $headers = $this->mailer->getHeaders();

        $this->assertArrayHasKey('Authorization', $headers);
        $this->assertEquals(self::SERVER_TOKEN, $headers['Authorization']);
    }

    public function testFormat()
    {
        $message = $this->getMessageMock(
            $from = 'noreply@example.com',
            $to = ['john@example.com', 'bob@example.com'],
            $subject = 'Testing SparkPost',
            $html = '<p>Hello</p>',
            $text = 'Hello',
            $headers = ['X-Foo' => 'bar']
        );

        $message
            ->expects($this->any())
            ->method('getReplyTo')
            ->will($this->returnValue('reply@example.com'));

        $message
            ->expects($this->any())
            ->method('getCc')
            ->will($this->returnValue(['charlie@example.com']));

        $message
            ->expects($this->any())
            ->method('getBcc')
            ->will($this->returnValue(['mark@example.com']));

        $this->assertArraySubset([
            'content' => [
                'from' => [
                    'name' => null,
                    'email' => 'noreply@example.com',
                ],
                'reply_to' => 'reply@example.com',
                'headers' => [
                    'X-Foo' => 'bar',
                    'Cc' => 'charlie@example.com',
                ],
                'subject' => 'Testing SparkPost',
                'text' => 'Hello',
                'html' => '<p>Hello</p>',
            ],
            'recipients' => [
                ['address' => ['email' => 'john@example.com']],
                ['address' => ['email' => 'bob@example.com']],
                ['address' => ['email' => 'charlie@example.com']],
                ['address' => ['email' => 'mark@example.com']],
            ],
        ], json_decode($this->mailer->format($message), true));
    }

    public function testFormatCarbonCopy()
    {
        $message = $this->getCarbonCopyMock(
            $from = null,
            $to = ['john@example.com', 'bob@example.com'],
            $subject = null,
            $html = null,
            $text = null,
            $headers = ['X-Foo' => 'bar'],
            $cc = ['charlie@example.com']
        );

        $this->assertArraySubset([
            'content' => [
                'headers' => [
                    'X-Foo' => 'bar',
                    'Cc' => 'charlie@example.com',
                ],
            ],
            'recipients' => [
                ['address' => ['email' => 'john@example.com']],
                ['address' => ['email' => 'bob@example.com']],
                ['address' => ['email' => 'charlie@example.com']],
            ],
        ], json_decode($this->mailer->format($message), true));
    }

    public function testFormatBlindCarbonCopy()
    {
        $message = $this->getBlindCarbonCopyMock(
            $from = null,
            $to = ['john@example.com', 'bob@example.com'],
            $subject = null,
            $html = null,
            $text = null,
            $headers = [],
            $bcc = ['mark@example.com']
        );

        $this->assertArraySubset([
            'recipients' => [
                ['address' => ['email' => 'john@example.com']],
                ['address' => ['email' => 'bob@example.com']],
                ['address' => ['email' => 'mark@example.com']],
            ],
        ], json_decode($this->mailer->format($message), true));
    }

    public function testFormatTaggable()
    {
        $message = $this->getTaggableMessageMock(
            $from = null,
            $to = 'john@example.com',
            $subject = null,
            $html = null,
            $text = null,
            $headers = [],
            $tag = ['foo', 'bar']
        );

        static::assertArraySubset([
            'recipients' => [
                [
                    'address' => ['email' => 'john@example.com'],
                    'tags' => ['foo', 'bar'],
                ],
            ],
        ], json_decode($this->mailer->format($message), true));
    }

    public function testFormatMetadata()
    {
        $message = $this->getMetadataAwareMessageMock(
            $from = null,
            $to = null,
            $subject = null,
            $html = null,
            $text = null,
            $headers = [],
            $metadata = ['foo' => 'bar', 'bar' => 'baz']
        );

        static::assertArraySubset([
            'metadata' => [
                'foo' => 'bar',
                'bar' => 'baz',
            ],
        ], json_decode($this->mailer->format($message), true));
    }

    public function testFormatAttachments()
    {
        $this->mailer = $this
            ->getMockBuilder(__NAMESPACE__.'\\TestSparkPost')
            ->setConstructorArgs([$this->adapter, self::SERVER_TOKEN])
            ->setMethods(['getAttachmentContent'])
            ->getMock();

        $message = $this->getAttachmentsMessageMock(
            $from = null,
            $to = null,
            $subject = null,
            $html = null,
            $text = null,
            $headers = [],
            array_merge(
                $attachments = [
                    $this->getAttachmentMock('files/paper.txt', 'paper.txt', 'text/plain', null),
                    $this->getAttachmentMock('files/apples.jpg', 'apples.jpg', 'image/jpeg', null),
                ],
                $images = [
                    $this->getAttachmentMock('files/oranges.jpg', 'oranges.jpg', 'image/jpeg', 'oranges'),
                ]
            )
        );

        static::assertArraySubset([
            'content' => [
                'inline_images' => [
                    [
                        'type' => 'image/jpeg',
                        'name' => 'oranges',
                    ],
                ],
                'attachments' => [
                    [
                        'type' => 'text/plain',
                        'name' => 'paper.txt',
                    ],
                    [
                        'type' => 'image/jpeg',
                        'name' => 'apples.jpg',
                    ],
                ],
            ],
        ], json_decode($this->mailer->format($message), true));
    }
}


class TestSparkPost extends SparkPost
{
    public function getEndpoint()
    {
        return parent::getEndpoint();
    }

    public function getHeaders()
    {
        return parent::getHeaders();
    }

    public function format(MessageInterface $message)
    {
        return parent::format($message);
    }

    public function handle(ResponseInterface $response)
    {
        parent::handle($response);
    }
}
