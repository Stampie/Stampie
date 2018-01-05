<?php

namespace Stampie\Tests\Mailer;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Http\Client\HttpClient;
use Stampie\Mailer\SendGrid;
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
        $this->mailer = new SendGrid($this->httpClient, self::SERVER_TOKEN);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInValidServerToken()
    {
        $this->mailer->setServerToken('invalid');
    }

    public function testSend()
    {
        $message = $this->getMessageMock('bob@example.com', 'alice@example.com', 'Stampie is awesome!', 'Trying out Stampie', null, [
            'X-Custom-Header' => 'My Custom Header Value',
        ]);

        $this->httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (Request $request) {
                return
                    $request->getMethod() === 'POST'
                    && (string) $request->getUri() === 'https://sendgrid.com/api/mail.send.json'
                    && $request->getHeaderLine('Content-Type') === 'application/x-www-form-urlencoded'
                    && (string) $request->getBody() === http_build_query([
                        'api_user' => 'rudolph',
                        'api_key' => 'rednose',
                        'to' => [
                            'alice@example.com',
                        ],
                        'from' => 'bob@example.com',
                        'subject' => 'Stampie is awesome!',
                        'html' => 'Trying out Stampie',
                        'headers' => json_encode([
                            'X-Custom-Header' => 'My Custom Header Value',
                        ]),
                    ])
                ;
            }))
            ->willReturn(new Response())
        ;

        $this->mailer->send($message);
    }

    public function testSendWithApiKeyContainingAColon()
    {
        $this->mailer->setServerToken('rudolph:rednose:reindeer');

        $message = $this->getMessageMock('bob@example.com', 'alice@example.com', 'Stampie is awesome!');

        $this->httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (Request $request) {
                return (string) $request->getBody() === http_build_query([
                    'api_user' => 'rudolph',
                    'api_key' => 'rednose:reindeer',
                    'to' => [
                        'alice@example.com',
                    ],
                    'from' => 'bob@example.com',
                    'subject' => 'Stampie is awesome!',
                ]);
            }))
            ->willReturn(new Response())
        ;

        $this->mailer->send($message);
    }

    public function testSendWithAttachments()
    {
        $message = $this->getAttachmentsMessageMock('bob@example.com', 'alice@example.com', 'Stampie is awesome!', null, null, [], [
            $this->getAttachmentMock('path-1.txt', 'path1.txt', 'text/plain', null),
            $this->getAttachmentMock('path-2.txt', 'path2.txt', 'text/plain', 'id1'),
            $this->getAttachmentMock('path-3.txt', 'path3.txt', 'text/plain', null),
        ]);

        $this->httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (Request $request) {
                $body = (string) $request->getBody();
                return
                    preg_match('#^multipart/form-data; boundary="[^"]+"$#', $request->getHeaderLine('Content-Type'))
                    && false !== strpos($body, 'Attachment #1')
                    && false !== strpos($body, 'Attachment #2')
                    && false !== strpos($body, 'Attachment #3')
                ;
            }))
            ->willReturn(new Response())
        ;

        $this->mailer->send($message);
    }

    public function testSendTaggable()
    {
        $message = $this->getTaggableMessageMock('bob@example.com', 'alice@example.com', 'Stampie is awesome!', null, null, [], ['tag']);

        $this->httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (Request $request) {
                return (string) $request->getBody() === http_build_query([
                    'api_user' => 'rudolph',
                    'api_key' => 'rednose',
                    'to' => [
                        'alice@example.com',
                    ],
                    'from' => 'bob@example.com',
                    'subject' => 'Stampie is awesome!',
                    'x-smtpapi' => json_encode(['category' => ['tag']])
                ]);
            }))
            ->willReturn(new Response())
        ;

        $this->mailer->send($message);
    }

    public function testSendMetadataAware()
    {
        $message = $this->getMetadataAwareMessageMock('bob@example.com', 'alice@example.com', 'Stampie is awesome!', null, null, [], ['client_name' => 'Stampie']);

        $this->httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (Request $request) {
                return (string) $request->getBody() === http_build_query([
                        'api_user' => 'rudolph',
                        'api_key' => 'rednose',
                        'to' => [
                            'alice@example.com',
                        ],
                        'from' => 'bob@example.com',
                        'subject' => 'Stampie is awesome!',
                        'x-smtpapi' => json_encode(['unique_args' => ['client_name' => 'Stampie']])
                    ]);
            }))
            ->willReturn(new Response())
        ;

        $this->mailer->send($message);
    }

    public function testSendEmptyMetadata()
    {
        $message = $this->getMetadataAwareMessageMock('bob@example.com', 'alice@example.com', 'Stampie is awesome!');

        $this->httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (Request $request) {
                return (string) $request->getBody() === http_build_query([
                        'api_user' => 'rudolph',
                        'api_key' => 'rednose',
                        'to' => [
                            'alice@example.com',
                        ],
                        'from' => 'bob@example.com',
                        'subject' => 'Stampie is awesome!',
                    ]);
            }))
            ->willReturn(new Response())
        ;

        $this->mailer->send($message);
    }

    /**
     * @expectedException \Stampie\Exception\ApiException
     */
    public function testHandleBadRequest()
    {
        $message = $this->getMessageMock('bob@example.com', 'alice@example.com', 'Stampie is awesome!');

        $response = new Response(400, [], '{ "errors" : ["Error In an Array"] }');

        $this->httpClient
            ->method('sendRequest')
            ->willReturn($response)
        ;

        $this->mailer->send($message);
    }

    /**
     * @expectedException \Stampie\Exception\HttpException
     */
    public function testHandleInternalServerError()
    {
        $message = $this->getMessageMock('bob@example.com', 'alice@example.com', 'Stampie is awesome!');

        $response = new Response(500);

        $this->httpClient
            ->method('sendRequest')
            ->willReturn($response)
        ;

        $this->mailer->send($message);
    }
}
