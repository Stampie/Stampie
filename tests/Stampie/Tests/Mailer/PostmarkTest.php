<?php

namespace Stampie\Tests\Mailer;

use Stampie\Mailer\Postmark;
use Stampie\Adapter\Response;
use Stampie\Adapter\ResponseInterface;
use Stampie\MessageInterface;

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
        $this->assertEquals(array(
            'Content-Type' => 'application/json',
            'X-Postmark-Server-Token' => $this->mailer->getServerToken(),
            'Accept' => 'application/json',
        ), $this->mailer->getHeaders());
    }

    public function testFormat()
    {
        $message = $this->getMessageMock(
            $from = 'hb@peytz.dk',
            $to = 'henrik@bjrnskov.dk',
            $subject = 'Stampie is awesome',
            $html = 'So what do you thing'
        );

        $this->assertEquals(json_encode(array(
            'From' => $from,
            'To' => $to,
            'Subject' => $subject,
            'HtmlBody' => $html,
        )), $this->mailer->format($message));
    }

    public function testFormatTaggable()
    {
        $message = $this->getTaggableMessageMock(
            $from = 'hb@peytz.dk',
            $to = 'henrik@bjrnskov.dk',
            $subject = 'Stampie is awesome',
            $html = 'So what do you thing',
            $text = 'text',
            $headers = array('X-Stampie-To' => 'henrik+to@bjrnskov.dk'),
            $tag = 'tag'
        );

        $formattedHeaders = array();
        foreach ($headers as $headerName => $headerValue) {
            $formattedHeaders[] = array( 'Name' => $headerName, 'Value' => $headerValue );
        }

        $this->assertEquals(json_encode(array(
            'From' => $from,
            'To' => $to,
            'Subject' => $subject,
            'Headers' => $formattedHeaders,
            'HtmlBody' => $html,
            'TextBody' => $text,
            'Tag' => $tag,
        )), $this->mailer->format($message));
    }

    public function testFormatAttachments()
    {
        $this->mailer = $this
                            ->getMockBuilder(__NAMESPACE__.'\\TestPostmark')
                            ->setConstructorArgs(array($this->adapter, self::SERVER_TOKEN))
                            ->setMethods(array('getAttachmentContent'))
                            ->getMock();

        $contentCallback = function($attachment){
            return 'content:'.$attachment->getPath();
        };

        $this->mailer
            ->expects($this->atLeastOnce())
            ->method('getAttachmentContent')
            ->will($this->returnCallback($contentCallback))
        ;

        $message = $this->getAttachmentsMessageMock(
            $from    = 'hb@peytz.dk',
            $to      = 'henrik@bjrnskov.dk',
            $subject = 'Stampie is awesome',
            $html    = 'So what do you thing',
            $text    = 'text',
            $headers = array('X-Stampie-To' => 'henrik+to@bjrnskov.dk'),
            array_merge(
                $attachments = array(
                    $this->getAttachmentMock('files/image-1.jpg', 'file1.jpg', 'image/jpeg', null),
                    $this->getAttachmentMock('files/image-2.jpg', 'file2.jpg', 'image/jpeg', null),
                ),
                $inline = array(
                    $this->getAttachmentMock('files/image-3.jpg', 'file3.jpg', 'image/jpeg', 'contentid1'),
                )
            )
        );

        $formattedAttachments = array();
        foreach ($attachments as $attachment) {
            $formattedAttachments[] = array(
                'Name'        => $attachment->getName(),
                'Content'     => base64_encode($contentCallback($attachment)),
                'ContentType' => $attachment->getType(),
            );
        }
        foreach ($inline as $attachment) {
            $formattedAttachments[] = array(
                'Name'        => $attachment->getName(),
                'Content'     => base64_encode($contentCallback($attachment)),
                'ContentType' => $attachment->getType(),
                'ContentID'   => $attachment->getId(),
            );
        }

        $formattedHeaders = array();
        foreach ($headers as $headerName => $headerValue) {
            $formattedHeaders[] = array( 'Name' => $headerName, 'Value' => $headerValue );
        }

        $this->assertEquals(json_encode(array(
            'From'        => $from,
            'To'          => $to,
            'Subject'     => $subject,
            'Headers'     => $formattedHeaders,
            'HtmlBody'    => $html,
            'TextBody'    => $text,
            'Attachments' => $formattedAttachments,
        )), $this->mailer->format($message));
    }

    public function testGetFiles()
    {
        $self  = $this; // PHP5.3 compatibility
        $adapter = $this->adapter;
        $token   = self::SERVER_TOKEN;
        $buildMocks = function($attachments, &$invoke) use($self, $adapter, $token){
            $mailer = $self->getMock('\\Stampie\\Mailer\\Postmark', null, array($adapter, $token));

            // Wrap protected method with accessor
            $mirror = new \ReflectionClass($mailer);
            $method = $mirror->getMethod('getFiles');
            $method->setAccessible(true);

            $invoke = function() use($mailer, $method){
                $args = func_get_args();
                array_unshift($args, $mailer);
                return call_user_func_array(array($method, 'invoke'), $args);
            };

            $message = $self->getAttachmentsMessageMock('test@example.com', 'other@example.com', 'Subject', null, null, array(), $attachments);

            return array($mailer, $message);
        };

        // Actual tests

        $attachments = array(
            $this->getAttachmentMock('path-1.txt', 'path1.txt', 'text/plain', null),
        );

        list($mailer, $message) = $buildMocks($attachments, $invoke);

        $this->assertEquals(array(), $invoke($message), 'Attachments should never be returned separately from body');
    }

    /**
     * @dataProvider handleDataProvider
     */
    public function testHandle($statusCode, $content, $exceptionType, $exceptionMessage)
    {
        $response = new Response($statusCode, $content);

        $this->setExpectedException($exceptionType, $exceptionMessage);

        $this->mailer->handle($response);
    }

    public function handleDataProvider()
    {
        return array(
            array(500, '', 'Stampie\Exception\HttpException', 'Internal Server Error'),
            array(400, '', 'Stampie\Exception\HttpException', 'Bad Request'),
            array(422, '{ "Message" : "Bad Credentials" }', 'Stampie\Exception\ApiException', 'Bad Credentials'),
        );
    }
}
