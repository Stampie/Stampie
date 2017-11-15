<?php

namespace Stampie\Tests\Mailer;

use Stampie\Adapter\Response;

class PostmarkTest extends \Stampie\Tests\BaseMailerTest
{
    const SERVER_TOKEN = '5daa75d9-8fad-4211-9b18-49124642732e';

    public function setUp()
    {
        parent::setUp();

        $this->mailer = new TestPostmark(
            $this->adapter,
            self::SERVER_TOKEN
        );
    }

    public function testEndpoint()
    {
        $this->assertEquals('http://api.postmarkapp.com/email', $this->mailer->getEndpoint());
    }

    public function testHeaders()
    {
        $this->assertEquals([
            'Content-Type'            => 'application/json',
            'X-Postmark-Server-Token' => $this->mailer->getServerToken(),
            'Accept'                  => 'application/json',
        ], $this->mailer->getHeaders());
    }

    public function testFormat()
    {
        $message = $this->getMessageMock(
            $from = 'hb@peytz.dk',
            $to = 'henrik@bjrnskov.dk',
            $subject = 'Stampie is awesome',
            $html = 'So what do you thing'
        );

        $this->assertEquals(json_encode([
            'From'     => $from,
            'To'       => $to,
            'Subject'  => $subject,
            'HtmlBody' => $html,
        ]), $this->mailer->format($message));
    }

    public function testFormatTaggable()
    {
        $message = $this->getTaggableMessageMock(
            $from = 'hb@peytz.dk',
            $to = 'henrik@bjrnskov.dk',
            $subject = 'Stampie is awesome',
            $html = 'So what do you thing',
            $text = 'text',
            $headers = ['X-Stampie-To' => 'henrik+to@bjrnskov.dk'],
            $tag = 'tag'
        );

        $formattedHeaders = [];
        foreach ($headers as $headerName => $headerValue) {
            $formattedHeaders[] = ['Name' => $headerName, 'Value' => $headerValue];
        }

        $this->assertEquals(json_encode([
            'From'     => $from,
            'To'       => $to,
            'Subject'  => $subject,
            'Headers'  => $formattedHeaders,
            'HtmlBody' => $html,
            'TextBody' => $text,
            'Tag'      => $tag,
        ]), $this->mailer->format($message));
    }

    public function testFormatAttachments()
    {
        $this->mailer = $this
                            ->getMockBuilder(__NAMESPACE__.'\\TestPostmark')
                            ->setConstructorArgs([$this->adapter, self::SERVER_TOKEN])
                            ->setMethods(['getAttachmentContent'])
                            ->getMock();

        $contentCallback = function ($attachment) {
            return 'content:'.$attachment->getPath();
        };

        $this->mailer
            ->expects($this->atLeastOnce())
            ->method('getAttachmentContent')
            ->will($this->returnCallback($contentCallback));

        $message = $this->getAttachmentsMessageMock(
            $from = 'hb@peytz.dk',
            $to = 'henrik@bjrnskov.dk',
            $subject = 'Stampie is awesome',
            $html = 'So what do you thing',
            $text = 'text',
            $headers = ['X-Stampie-To' => 'henrik+to@bjrnskov.dk'],
            array_merge(
                $attachments = [
                    $this->getAttachmentMock('files/image-1.jpg', 'file1.jpg', 'image/jpeg', null),
                    $this->getAttachmentMock('files/image-2.jpg', 'file2.jpg', 'image/jpeg', null),
                ],
                $inline = [
                    $this->getAttachmentMock('files/image-3.jpg', 'file3.jpg', 'image/jpeg', 'contentid1'),
                ]
            )
        );

        $formattedAttachments = [];
        foreach ($attachments as $attachment) {
            $formattedAttachments[] = [
                'Name'        => $attachment->getName(),
                'Content'     => base64_encode($contentCallback($attachment)),
                'ContentType' => $attachment->getType(),
            ];
        }
        foreach ($inline as $attachment) {
            $formattedAttachments[] = [
                'Name'        => $attachment->getName(),
                'Content'     => base64_encode($contentCallback($attachment)),
                'ContentType' => $attachment->getType(),
                'ContentID'   => $attachment->getId(),
            ];
        }

        $formattedHeaders = [];
        foreach ($headers as $headerName => $headerValue) {
            $formattedHeaders[] = ['Name' => $headerName, 'Value' => $headerValue];
        }

        $this->assertEquals(json_encode([
            'From'        => $from,
            'To'          => $to,
            'Subject'     => $subject,
            'Headers'     => $formattedHeaders,
            'HtmlBody'    => $html,
            'TextBody'    => $text,
            'Attachments' => $formattedAttachments,
        ]), $this->mailer->format($message));
    }

    public function testGetFiles()
    {
        $self = $this; // PHP5.3 compatibility
        $adapter = $this->adapter;
        $token = self::SERVER_TOKEN;
        $buildMocks = function ($attachments, &$invoke) use ($self, $adapter, $token) {
            $mailer = $self->getMockBuilder('\\Stampie\\Mailer\\Postmark')
                ->setConstructorArgs([$adapter, $token])
                ->getMock()
            ;

            // Wrap protected method with accessor
            $mirror = new \ReflectionClass($mailer);
            $method = $mirror->getMethod('getFiles');
            $method->setAccessible(true);

            $invoke = function () use ($mailer, $method) {
                $args = func_get_args();
                array_unshift($args, $mailer);

                return call_user_func_array([$method, 'invoke'], $args);
            };

            $message = $self->getAttachmentsMessageMock('test@example.com', 'other@example.com', 'Subject', null, null, [], $attachments);

            return [$mailer, $message];
        };

        // Actual tests

        $attachments = [
            $this->getAttachmentMock('path-1.txt', 'path1.txt', 'text/plain', null),
        ];

        list($mailer, $message) = $buildMocks($attachments, $invoke);

        $this->assertEquals([], $invoke($message), 'Attachments should never be returned separately from body');
    }

    /**
     * @expectedException \Stampie\Exception\HttpException
     * @expectedExceptionMessage Internal Server Error
     */
    public function testHandleInternalServerError()
    {
        $this->mailer->handle(new Response(500, ''));
    }

    /**
     * @expectedException \Stampie\Exception\HttpException
     * @expectedExceptionMessage Bad Request
     */
    public function testHandlerBadRequest()
    {
        $this->mailer->handle(new Response(400, ''));
    }

    /**
     * @expectedException \Stampie\Exception\ApiException
     * @expectedExceptionMessage Bad Credentials
     */
    public function testHandleBadCredentials()
    {
        $this->mailer->handle(new Response(422, '{ "Message" : "Bad Credentials" }'));
    }
}
