<?php

namespace Stampie\Tests;

use Http\Client\HttpClient;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Stampie\Mailer;
use Stampie\Mailer\MailGun;
use Stampie\MessageInterface;

class MailerTest extends TestCase
{
    protected $httpClient;

    /**
     * @var \Stampie\MailerInterface
     */
    protected $mailer;

    protected function setUp(): void
    {
        $this->mailer = $this->getMailerMock([
            $this->httpClient = $this->getHttpClientMock(),
            'Token',
        ]);
    }

    public function testSettersAndGetters()
    {
        $reflectionProperty = new \ReflectionProperty($this->mailer, 'httpClient');
        $reflectionProperty->setAccessible(true);

        $this->assertEquals('Token', $this->mailer->getServerToken());
        $this->assertEquals($this->httpClient, $reflectionProperty->getValue($this->mailer));
    }

    public function testServerTokenCannotBeEmpty()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('ServerToken cannot be empty');
        $this->mailer->setServerToken('');
    }

    public function testSendSuccessful()
    {
        $mailer = $this->mailer;
        $adapter = $this->httpClient;

        $mailer
            ->expects($this->once())
            ->method('format');

        $mailer
            ->expects($this->never())
            ->method('handle');

        $adapter
            ->expects($this->once())
            ->method('sendRequest')
            ->will($this->returnValue(
                $this->getResponseMock(true)
            ));

        $this->mailer->send($this->getMockBuilder(MessageInterface::class)->getMock());
    }

    public function testUnsuccessfulSendCallsHandle()
    {
        $this
            ->httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->will($this->returnValue($this->getResponseMock(false)));

        $this
            ->mailer
            ->expects($this->once())
            ->method('handle');

        $this->mailer->send($this->getMockBuilder(MessageInterface::class)->getMock());
    }

    public function testSendWithFiles()
    {
        $adapter = $this->httpClient;
        $mailer = $this->getMockBuilder(MailGun::class)
            ->setConstructorArgs([$adapter, 'Token:bar'])
            ->onlyMethods(['format', 'getFiles'])
            ->getMock();

        $mailer
            ->expects($this->once())
            ->method('format')
            ->willReturn('');

        $mailer
            ->expects($this->once())
            ->method('getFiles')
            ->willReturn(['files' => ['foo' => __DIR__.'/../../Fixtures/logo.png']]);

        $adapter
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (RequestInterface $request) {
                return (bool) preg_match('|multipart/form-data; boundary="[a-zA-Z0-9\._]+"|sim', $request->getHeaderLine('Content-Type'));
            }))
            ->will($this->returnValue(
                $this->getResponseMock(true)
            ));

        $mailer->send($this->getMockBuilder(MessageInterface::class)->getMock());
    }

    /**
     * @param bool $isSuccessfull
     *
     * @return ResponseInterface
     */
    protected function getResponseMock($isSuccessfull)
    {
        $response = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $stream = $this->getMockBuilder(StreamInterface::class)->getMock();
        $stream->method('__toString')->willReturn('stream content');

        $response->method('getStatusCode')->willReturn($isSuccessfull ? 200 : 400);
        $response->method('getBody')->willReturn($stream);

        return $response;
    }

    protected function getHttpClientMock()
    {
        return $this->getMockBuilder(HttpClient::class)->getMock();
    }

    protected function getMailerMock(array $args = [])
    {
        $mailer = $this->getMockForAbstractClass(Mailer::class, $args);

        $mailer->expects($this->any())
            ->method('getEndpoint')
            ->willReturn('https://example.com/fake-endpoint');

        $mailer->method('format')
            ->willReturn('');

        return $mailer;
    }
}
