<?php

namespace Stampie\Tests;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class MailerTest extends \PHPUnit_Framework_TestCase
{
    protected $httpClient;

    /**
     * @var \Stampie\MailerInterface
     */
    protected $mailer;

    public function setUp()
    {
        $this->mailer = $this->getMailerMock(array(
            $this->httpClient = $this->getHttpClientMock(),
            'Token',
        ));
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
        $this->setExpectedException('InvalidArgumentException', 'ServerToken cannot be empty');
        $this->mailer->setServerToken('');
    }

    public function testSendSuccessful()
    {
        $mailer = $this->mailer;
        $adapter = $this->httpClient;

        $mailer
            ->expects($this->once())
            ->method('format')
        ;

        $mailer
            ->expects($this->never())
            ->method('handle')
        ;

        $adapter
            ->expects($this->once())
            ->method('sendRequest')
            ->will($this->returnValue(
                $this->getResponseMock(true)
            ))
        ;

        $this->assertTrue($this->mailer->send($this->getMock('Stampie\MessageInterface')));
    }

    public function testUnsuccessfulSendCallsHandle()
    {
        $this
            ->httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->will($this->returnValue($this->getResponseMock(false)))
        ;

        $this
            ->mailer
            ->expects($this->once())
            ->method('handle')
        ;

        $this->mailer->send($this->getMock('Stampie\MessageInterface'));
    }

    /**
     * @param bool $isSuccessfull
     *
     * @return ResponseInterface
     */
    protected function getResponseMock($isSuccessfull)
    {
        $response = $this->getMock(ResponseInterface::class);
        $stream = $this->getMock(StreamInterface::class);
        $stream->method('__toString')->willReturn('stream content');

        $response->method('getStatusCode')->willReturn($isSuccessfull ? 200 : 400);
        $response->method('getBody')->willReturn($stream);

        return $response;

    }

    protected function getHttpClientMock()
    {
        return $this->getMock('Http\Client\HttpClient');
    }

    protected function getMailerMock(array $args = array())
    {
        return $this->getMockForAbstractClass('Stampie\Mailer', $args);
    }
}
