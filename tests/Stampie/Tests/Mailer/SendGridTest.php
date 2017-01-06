<?php

namespace Stampie\Tests\Mailer;

use Stampie\Mailer\SendGrid;
use Stampie\Adapter\Response;
use Stampie\Adapter\ResponseInterface;
use Stampie\MessageInterface;

class SendGridTest extends \Stampie\Tests\BaseMailerTest
{
    const SERVER_TOKEN = 'rudolph:rednose';

    public function setUp()
    {
        parent::setUp();

        $this->mailer = new TestSendGrid(
            $this->adapter,
            self::SERVER_TOKEN
        );
    }

    public function testInValidServerToken()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->mailer->setServerToken('invalid');
    }

    public function testPasswordContainingTokenSeparator()
    {
        $this->mailer->setServerToken('rudolph:rednose:reindeer');

        $message =  $this->getMessageMock(
            $from = 'john@example.com',
            $to = 'jane@example.com',
            $subject = 'Testing password that contains :',
            $html = 'Stampie is Awesome'
        );

        $this->assertContains('api_key=rednose:reindeer', urldecode($this->mailer->format($message)));
    }

    public function testEndpoint()
    {
        $this->assertEquals('https://sendgrid.com/api/mail.send.json', $this->mailer->getEndpoint());
    }

    /**
     * @dataProvider handleDataProvider
     */
    public function testHandle($statusCode, $content, $exceptionType)
    {
        $response = new Response($statusCode, $content);
        $this->setExpectedException($exceptionType);

        $this->mailer->handle($response);
    }

    public function testFormat()
    {
        $api_user = 'rudolph';
        $api_key = 'rednose';

        $message =  $this->getMessageMock(
            $from = 'henrik@bjrnskov.dk',
            $to = 'hb@peytz.dk',
            $subject = 'Trying out Stampie',
            $html = 'Stampie is Awesome',
            $text = '',
            $headers = array(
                'X-Custom-Header' => 'My Custom Header Value',
            )
        );

        $headers = json_encode($headers);
        $to = array($to);

        $query = compact(
            'api_user', 'api_key', 'to', 'from', 'subject', 'html', 'headers'
        );


        $this->assertEquals(http_build_query(
            $query
        ), $this->mailer->format($message));
    }

    public function testFormatTaggable()
    {
        $api_user = 'rudolph';
        $api_key = 'rednose';

        $message =  $this->getTaggableMessageMock(
            $from = 'henrik@bjrnskov.dk',
            $to = 'hb@peytz.dk',
            $subject = 'Trying out Stampie',
            $html = 'Stampie is Awesome',
            $text = '',
            $headers = array(
                'X-Custom-Header' => 'My Custom Header Value',
            ),
            $tag = 'tag'
        );

        $headers = json_encode($headers);
        $to = array($to);

        $query = compact(
            'api_user', 'api_key', 'to', 'from', 'subject', 'html', 'headers'
        );
        $query['x-smtpapi'] = json_encode(array('category' => array($tag)));


        $this->assertEquals(http_build_query(
            $query
        ), $this->mailer->format($message));
    }

    public function testFormatMetadataAware()
    {
        $api_user = 'rudolph';
        $api_key = 'rednose';

        $message =  $this->getMetadataAwareMessageMock(
            $from = 'henrik@bjrnskov.dk',
            $to = 'hb@peytz.dk',
            $subject = 'Trying out Stampie',
            $html = 'Stampie is Awesome',
            $text = '',
            $headers = array(
                'X-Custom-Header' => 'My Custom Header Value',
            ),
            $metadata = array('client_name' => 'Stampie')
        );

        $headers = json_encode($headers);
        $to = array($to);

        $query = compact(
            'api_user', 'api_key', 'to', 'from', 'subject', 'html', 'headers'
        );
        $query['x-smtpapi'] = json_encode(array('unique_args' => $metadata));


        $this->assertEquals(http_build_query(
            $query
        ), $this->mailer->format($message));
    }

    public function testFormatAttachments()
    {
        $api_user = 'rudolph';
        $api_key = 'rednose';

        $message =  $this->getAttachmentsMessageMock(
            $from    = 'henrik@bjrnskov.dk',
            $to      = 'hb@peytz.dk',
            $subject = 'Trying out Stampie',
            $html    = 'Stampie is Awesome',
            $text    = '',
            $headers = array(
                'X-Custom-Header' => 'My Custom Header Value',
            ),
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

        $headers = json_encode($headers);
        $to = array($to);


        $processedInline = array();
        foreach ($inline as $attachment){
            $processedInline[$attachment->getId()] = $attachment->getName();
        }
        $content = $processedInline;

        $query = compact(
            'api_user', 'api_key', 'to', 'from', 'subject', 'html', 'content', 'headers'
        );

        $this->assertEquals(http_build_query(
            $query
        ), $this->mailer->format($message));
    }

    public function testGetFiles()
    {
        $self  = $this; // PHP5.3 compatibility
        $adapter = $this->adapter;
        $token   = self::SERVER_TOKEN;
        $buildMocks = function($attachments, &$invoke) use($self, $adapter, $token){
            $mailer = $self->getMock('\\Stampie\\Mailer\\SendGrid', null, array($adapter, $token));

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
            $this->getAttachmentMock('path-2.txt', 'path2.txt', 'text/plain', 'id1'),
            $this->getAttachmentMock('path-3.txt', 'path3.txt', 'text/plain', null),
            $this->getAttachmentMock('path-4.txt', 'path4.txt', 'text/plain', 'id2'),
            $this->getAttachmentMock('path-5.txt', 'path5.txt', 'text/plain', null),
        );

        list($mailer, $message) = $buildMocks($attachments, $invoke);
        $result = $invoke($message);

        $this->assertEquals(count($attachments), count($result['files']), 'All attachments should be returned');

        $i = 0;
        foreach ($result['files'] as $name => $path) {
            $this->assertEquals($attachments[$i]->getName(), $name, 'Attachments should be formatted correctly');
            $this->assertEquals($attachments[$i]->getPath(), $path, 'Attachments should be formatted correctly');
            $i++;
        }
    }

    public function handleDataProvider()
    {
        return array(
            array(400, '{ "errors" : ["Error In an Array"] }', 'Stampie\Exception\ApiException'),
            array(500, '', 'Stampie\Exception\HttpException')
        );
    }
}

class TestSendGrid extends SendGrid
{
    public function getEndpoint()
    {
        return parent::getEndpoint();
    }

    public function handle(ResponseInterface $response)
    {
        parent::handle($response);
    }

    public function format(MessageInterface $message)
    {
        return parent::format($message);
    }
}
