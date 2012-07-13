<?php

namespace Stampie\Tests\Mailer;

use Stampie\Mailer\PeytzMail;
use Stampie\Adapter\Response;
use Stampie\Adapter\ResponseInterface;
use Stampie\MessageInterface;

abstract class TaggableMessage implements \Stampie\MessageInterface, \Stampie\Message\TaggableInterface
{
}

/**
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class PeytzMailTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->adapter = $this->getMock('Stampie\Adapter\AdapterInterface');
        $this->mailer = new TestPeytzMail($this->adapter, 'something:something');
    }

    public function testSendThrowsExceptionWithInvalidMessageImplementation()
    {
        $this->setExpectedException('InvalidArgumentException');

        $this->mailer->send($this->getMock('Stampie\MessageInterface'));
    }

    public function testSend()
    {
        $message = $this->getMockForAbstractClass('Stampie\Tests\Mailer\TaggableMessage');

        $this->adapter
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue(new Response(200, '')))
        ;

        $this->mailer->send($message);
    }


    public function testFormat()
    {
        $message = $this->getMockForAbstractClass('Stampie\Tests\Mailer\TaggableMessage');

        $message->expects($this->once())->method('getHtml')->will($this->returnValue('html'));
        $message->expects($this->once())->method('getText')->will($this->returnValue('text'));
        $message->expects($this->once())->method('getTag')->will($this->returnValue('tag'));
        $message->expects($this->once())->method('getFrom')->will($this->returnValue('henrik@bjrnskov.dk'));
        $message->expects($this->once())->method('getTo')->will($this->returnValue('henrik+to@bjrnskov.dk'));

        $this->mailer->format($message);
    }

    public function testInvalidServerToken()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->mailer->setServerToken('invalid-servertoken');
    }

    public function testEndpoint()
    {
        $this->mailer->setServerToken('peytz:apikey');
        $this->assertEquals('https://peytz.peytzmail.com/api/v1/trigger_mails.json', $this->mailer->getEndpoint());
    }

    public function testHeaders()
    {
        $this->assertEquals(array(
            'Accept' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode('something:'),
            'Content-Type' => 'application/json',
        ), $this->mailer->getHeaders());
    }

    public function testHandle()
    {
        $this->setExpectedException('Stampie\Exception\HttpException');

        $response = new Response(401, 'Unauthorized');

        $this->mailer->handle($response);
    }
}

class TestPeytzMail extends PeytzMail
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
