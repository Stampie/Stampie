<?php

namespace Stampie\Tests\Mailer;

use Stampie\Mailer\Mandrill;
use Stampie\Adapter\Response;
use Stampie\Adapter\ResponseInterface;
use Stampie\MessageInterface;

class MandrillTest extends \Stampie\Tests\BaseMailerTest
{
    const SERVER_TOKEN = '5daa75d9-8fad-4211-9b18-49124642732e';

    public function setUp()
    {
        parent::setUp();

        $this->mailer = new TestMandrill(
            $this->adapter,
            self::SERVER_TOKEN
        );
    }

    public function testEndpoint()
    {
        $this->assertEquals('https://mandrillapp.com/api/1.0/messages/send.json', $this->mailer->getEndpoint());
    }

    public function testHeaders()
    {
        $this->assertEquals(array(
            'Content-Type' => 'application/json',
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
            'key' => self::SERVER_TOKEN,
            'message' => array(
                'from_email' => $from,
                'to' => array(array('email' => $to, 'name' => null)),
                'subject' => $subject,
                'html' => $html,
            ),
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

        $this->assertEquals(json_encode(array(
            'key' => self::SERVER_TOKEN,
            'message' => array(
                'from_email' => $from,
                'to' => array(array('email' => $to, 'name' => null)),
                'subject' => $subject,
                'headers' => $headers,
                'text' => $text,
                'html' => $html,
                'tags' => array($tag)
            ),
        )), $this->mailer->format($message));
    }

    public function testFormatMetadataAware()
    {
        $message = $this->getMetadataAwareMessageMock(
            $from = 'hb@peytz.dk',
            $to = 'henrik@bjrnskov.dk',
            $subject = 'Stampie is awesome',
            $html = 'So what do you thing',
            $text = 'text',
            $headers = array('X-Stampie-To' => 'henrik+to@bjrnskov.dk'),
            $metadata = array('client_name' => 'Stampie')
        );

        $this->assertEquals(json_encode(array(
            'key' => self::SERVER_TOKEN,
            'message' => array(
                'from_email' => $from,
                'to' => array(array('email' => $to, 'name' => null)),
                'subject' => $subject,
                'headers' => $headers,
                'text' => $text,
                'html' => $html,
                'metadata' => $metadata
            ),
        )), $this->mailer->format($message));
    }

    public function testFormatAttachments()
    {
        $this->mailer = $this
                            ->getMockBuilder(__NAMESPACE__.'\\TestMandrill')
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
            $from = 'hb@peytz.dk',
            $to = 'henrik@bjrnskov.dk',
            $subject = 'Stampie is awesome',
            $html = 'So what do you thing',
            $text = 'text',
            $headers = array('X-Stampie-To' => 'henrik+to@bjrnskov.dk'),
            array_merge(
                $attachments = array(
                    $this->getAttachmentMock('files/image-1.jpg', 'file1.jpg', 'image/jpeg', null),
                    $this->getAttachmentMock('files/image-2.jpg', 'file2.jpg', 'image/jpeg', null),
                ),
                $images = array(
                    $this->getAttachmentMock('files/image-3.jpg', 'file3.jpg', 'image/jpeg', 'contentid1'),
                )
            )
        );

        $processedAttachments = array();
        foreach ($attachments as $attachment) {
            $processedAttachments[] = array(
                'type'    => $attachment->getType(),
                'name'    => $attachment->getName(),
                'content' => base64_encode($contentCallback($attachment)),
            );
        }

        $processedImages = array();
        foreach ($images as $attachment) {
            $processedImages[] = array(
                'type'    => $attachment->getType(),
                'name'    => $attachment->getID(),
                'content' => base64_encode($contentCallback($attachment)),
            );
        }

        $this->assertEquals(json_encode(array(
            'key' => self::SERVER_TOKEN,
            'message' => array(
                'from_email' => $from,
                'to' => array(array('email' => $to, 'name' => null)),
                'subject' => $subject,
                'headers' => $headers,
                'text' => $text,
                'html' => $html,
                'attachments' => $processedAttachments,
                'images' => $processedImages,
            ),
        )), $this->mailer->format($message));
    }

    public function testGetFiles(){
        $self  = $this; // PHP5.3 compatibility
        $adapter = $this->adapter;
        $token   = self::SERVER_TOKEN;
        $buildMocks = function($attachments, &$invoke) use($self, $adapter, $token){
            $mailer = $self->getMock('\\Stampie\\Mailer\\Mandrill', null, array($adapter, $token));

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
    public function testHandle($statusCode, $content)
    {
        $response = new Response($statusCode, json_encode(array('message' => $content, 'code' => -1)));

        try {
            $this->mailer->handle($response);
        } catch (\Stampie\Exception\ApiException $e) {
            $this->assertInstanceOf('Stampie\Exception\HttpException', $e->getPrevious());
            $this->assertEquals($e->getPrevious()->getMessage(), $content);
            $this->assertEquals($e->getMessage(), $content);
            return;
        }

        $this->fail('Expected Stampie\Exception\ApiException to be trown');
    }

    public function handleDataProvider()
    {
        return array(
            array(400, 'Bad Request'),
            array(401, 'Unauthorized'),
            array(504, 'Gateway Timeout'),
        );
    }
}

class TestMandrill extends Mandrill
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
