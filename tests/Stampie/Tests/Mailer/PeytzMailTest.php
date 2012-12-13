<?php

namespace Stampie\Tests\Mailer;

use Stampie\Mailer\PeytzMail;
use Stampie\Tests\BaseMailerTest;
use Stampie\Adapter\Response;
use Stampie\Adapter\ResponseInterface;
use Stampie\MessageInterface;

/**
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class PeytzMailTest extends BaseMailerTest
{
    protected $adapter;

    /**
     * @var TestPeytzMail
     */
    protected $mailer;

    public function setUp()
    {
        parent::setUp();

        $this->mailer = new TestPeytzMail($this->adapter, 'something:something');
    }

    public function testSendThrowsExceptionWithInvalidMessageImplementation()
    {
        $this->setExpectedException('InvalidArgumentException');

        $this->mailer->send($this->getMock('Stampie\MessageInterface'));
    }

    public function testSend()
    {
        $message = $this->getTaggableMessageMock('henrik@bjrnskov.dk', 'henrik+to@bjrnskov.dk', 'Stampie is awesome', 'html', 'text', array(), 'tag');

        $this->adapter
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue(new Response(200, '')))
        ;

        $this->mailer->send($message);
    }


    public function testFormat()
    {
        $message = $this->getTaggableMessageMock('henrik@bjrnskov.dk', 'henrik+to@bjrnskov.dk', 'Stampie is awesome', 'html', 'text', array(), 'tag');

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
