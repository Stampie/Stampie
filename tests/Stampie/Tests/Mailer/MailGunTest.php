<?php

namespace Stampie\Tests\Mailer;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Http\Client\HttpClient;
use Stampie\Mailer\MailGun;
use Stampie\Tests\TestCase;

/**
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class MailGunTest extends TestCase
{
    const SERVER_TOKEN = 'henrik.bjrnskov.dk:myCustomKey';

    /**
     * @var MailGun
     */
    private $mailer;

    /**
     * @var HttpClient|\PHPUnit_Framework_MockObject_MockObject
     */
    private $httpClient;

    public function setUp()
    {
        $this->httpClient = $this->getMockBuilder(HttpClient::class)->getMock();
        $this->mailer = new MailGun($this->httpClient, self::SERVER_TOKEN);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testServerTokenMissingDelimeter()
    {
        new MailGun($this->httpClient, 'missingDelimeter');
    }

    public function testServerToken()
    {
        $this->assertEquals(self::SERVER_TOKEN, $this->mailer->getServerToken());
    }

    public function testSend()
    {
        $response = new Response();

        $message = $this->getMessageMock('bob@example.com', 'alice@example.com', 'Stampie is awesome!');

        $this->httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (Request $request) {
                return
                    $request->getMethod() === 'POST'
                    && (string) $request->getUri() === 'https://api.mailgun.net/v2/henrik.bjrnskov.dk/messages'
                    && $request->getHeaderLine('Content-Type') === 'application/x-www-form-urlencoded'
                    && $request->getHeaderLine('Authorization') === 'Basic '.base64_encode('api:myCustomKey')
                ;
            }))
            ->willReturn($response)
        ;

        $this->mailer->send($message);
    }

    public function testSendWithAttachments()
    {
        $response = new Response();

        $message = $this->getAttachmentsMessageMock('bob@example.com', 'alice@example.com', 'Stampie is awesome', null, null, [], [
            $this->getAttachmentMock('path-1.txt', 'path1.txt', 'text/plain', null),
            $this->getAttachmentMock('path-2.txt', 'path2.txt', 'text/plain', 'id1'),
            $this->getAttachmentMock('path-3.txt', 'path3.txt', 'text/plain', null),
            $this->getAttachmentMock('path-4.txt', 'path4.txt', 'text/plain', 'id2'),
            $this->getAttachmentMock('path-5.txt', 'path5.txt', 'text/plain', null),
        ]);

        $this->httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (Request $request) {
                $body = (string) $request->getBody();
                return
                    preg_match('#^multipart/form-data; boundary="[^"]+"$#', $request->getHeaderLine('Content-Type'), $matches)
                    && false !== strpos($body, 'Attachment #1')
                    && false !== strpos($body, 'Attachment #2')
                    && false !== strpos($body, 'Attachment #3')
                    && false !== strpos($body, 'Attachment #4')
                    && false !== strpos($body, 'Attachment #5')
                ;
            }))
            ->willReturn($response)
        ;

        $this->mailer->send($message);
    }
}
