<?php

namespace Stampie\Tests;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Stampie\Mailer\MailGun;

class MailerTest extends TestCase
{
    protected $httpClient;

    /**
     * @var \Stampie\MailerInterface
     */
    protected $mailer;

    public function setUp()
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

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage ServerToken cannot be empty
     */
    public function testServerTokenCannotBeEmpty()
    {
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

        $this->mailer->send($this->getMockBuilder('Stampie\MessageInterface')->getMock());
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

        $this->mailer->send($this->getMockBuilder('Stampie\MessageInterface')->getMock());
    }

    public function testSendWithFiles()
    {
        $adapter = $this->httpClient;
        $mailer = $this->getMockBuilder(MailGun::class)
            ->setConstructorArgs([$adapter, 'Token:bar'])
            ->setMethods(['format', 'getFiles'])
            ->getMock();

        $mailer
            ->expects($this->once())
            ->method('format');

        $mailer
            ->expects($this->once())
            ->method('getFiles')
            ->willReturn(['files' => ['foo' => __DIR__.'/../../Fixtures/logo.png']]);

        $adapter
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (RequestInterface $request) {
                return preg_match('|multipart/form-data; boundary="[a-zA-Z0-9\._]+"|sim', $request->getHeaderLine('Content-Type'));
            }))
            ->will($this->returnValue(
                $this->getResponseMock(true)
            ));

        $mailer->send($this->getMockBuilder('Stampie\MessageInterface')->getMock());
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
        return $this->getMockBuilder('Http\Client\HttpClient')->getMock();
    }

    protected function getMailerMock(array $args = [])
    {
        $mailer = $this->getMockForAbstractClass('Stampie\Mailer', $args);

        $mailer->expects($this->any())
            ->method('getEndpoint')
            ->willReturn('https://example.com/fake-endpoint');

        return $mailer;
    }
}
