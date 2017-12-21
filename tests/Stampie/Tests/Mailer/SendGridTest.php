<?php

namespace Stampie\Tests\Mailer;

use Http\Client\HttpClient;
use Stampie\Adapter\Response;
use Stampie\Adapter\ResponseInterface;
use Stampie\Mailer\SendGrid;
use Stampie\MessageInterface;
use Stampie\Tests\TestCase;

class SendGridTest extends TestCase
{
    const SERVER_TOKEN = 'rudolph:rednose';

    /**
     * @var SendGrid
     */
    private $mailer;

    /**
     * @var HttpClient|\PHPUnit_Framework_MockObject_MockObject
     */
    private $httpClient;

    public function setUp()
    {
        $this->httpClient = $this->getMockBuilder(HttpClient::class)->getMock();
        $this->mailer = new TestSendGrid(
            $this->httpClient,
            self::SERVER_TOKEN
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInValidServerToken()
    {
        $this->mailer->setServerToken('invalid');
    }

    public function testPasswordContainingTokenSeparator()
    {
        $this->mailer->setServerToken('rudolph:rednose:reindeer');

        $message = $this->getMessageMock(
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
     * @expectedException \Stampie\Exception\ApiException
     */
    public function testHandleBadRequest()
    {
        $this->mailer->handle(new Response(400, '{ "errors" : ["Error In an Array"] }'));
    }

    /**
     * @expectedException \Stampie\Exception\HttpException
     */
    public function testHandleInternalServerError()
    {
        $this->mailer->handle(new Response(500, ''));
    }

    public function testFormat()
    {
        $api_user = 'rudolph';
        $api_key = 'rednose';

        $message = $this->getMessageMock(
            $from = 'henrik@bjrnskov.dk',
            $to = 'hb@peytz.dk',
            $subject = 'Trying out Stampie',
            $html = 'Stampie is Awesome',
            $text = '',
            $headers = [
                'X-Custom-Header' => 'My Custom Header Value',
            ]
        );

        $headers = json_encode($headers);
        $to = [$to];

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

        $message = $this->getTaggableMessageMock(
            $from = 'henrik@bjrnskov.dk',
            $to = 'hb@peytz.dk',
            $subject = 'Trying out Stampie',
            $html = 'Stampie is Awesome',
            $text = '',
            $headers = [
                'X-Custom-Header' => 'My Custom Header Value',
            ],
            $tag = 'tag'
        );

        $headers = json_encode($headers);
        $to = [$to];

        $query = compact(
            'api_user', 'api_key', 'to', 'from', 'subject', 'html', 'headers'
        );
        $query['x-smtpapi'] = json_encode(['category' => [$tag]]);

        $this->assertEquals(http_build_query(
            $query
        ), $this->mailer->format($message));
    }

    public function testFormatMetadataAware()
    {
        $api_user = 'rudolph';
        $api_key = 'rednose';

        $message = $this->getMetadataAwareMessageMock(
            $from = 'henrik@bjrnskov.dk',
            $to = 'hb@peytz.dk',
            $subject = 'Trying out Stampie',
            $html = 'Stampie is Awesome',
            $text = '',
            $headers = [
                'X-Custom-Header' => 'My Custom Header Value',
            ],
            $metadata = ['client_name' => 'Stampie']
        );

        $headers = json_encode($headers);
        $to = [$to];

        $query = compact(
            'api_user', 'api_key', 'to', 'from', 'subject', 'html', 'headers'
        );
        $query['x-smtpapi'] = json_encode(['unique_args' => $metadata]);

        $this->assertEquals(http_build_query(
            $query
        ), $this->mailer->format($message));
    }

    public function testFormatEmptyMetadata()
    {
        $message = $this->getMetadataAwareMessageMock(
            $from = 'henrik@bjrnskov.dk',
            $to = 'hb@peytz.dk',
            $subject = 'Trying out Stampie',
            $html = 'Stampie is Awesome',
            $text = '',
            $headers = [],
            $metadata = []
        );

        $this->assertNotContains('x-smtpapi', $this->mailer->format($message));
    }

    public function testFormatAttachments()
    {
        $api_user = 'rudolph';
        $api_key = 'rednose';

        $message = $this->getAttachmentsMessageMock(
            $from = 'henrik@bjrnskov.dk',
            $to = 'hb@peytz.dk',
            $subject = 'Trying out Stampie',
            $html = 'Stampie is Awesome',
            $text = '',
            $headers = [
                'X-Custom-Header' => 'My Custom Header Value',
            ],
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

        $headers = json_encode($headers);
        $to = [$to];

        $processedInline = [];
        foreach ($inline as $attachment) {
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
        $self = $this; // PHP5.3 compatibility
        $adapter = $this->httpClient;
        $token = self::SERVER_TOKEN;
        $buildMocks = function ($attachments, &$invoke) use ($self, $adapter, $token) {
            $mailer = $self->getMockBuilder('\\Stampie\\Mailer\\SendGrid')
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
            $this->getAttachmentMock('path-2.txt', 'path2.txt', 'text/plain', 'id1'),
            $this->getAttachmentMock('path-3.txt', 'path3.txt', 'text/plain', null),
            $this->getAttachmentMock('path-4.txt', 'path4.txt', 'text/plain', 'id2'),
            $this->getAttachmentMock('path-5.txt', 'path5.txt', 'text/plain', null),
        ];

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
