<?php

namespace Stampie\Tests\Mailer;

use Stampie\Mailer\SendGrid;
use Stampie\Adapter\Response;

class SendGridTest extends \Stampie\Tests\BaseMailerTest
{
    public function testServerTokenMissingDelimiter()
    {
        $this->setExpectedException('InvalidArgumentException', 'SendGrid uses a "username:password" based ServerToken');
        $mailer = new SendGrid($this->adapter, '');
    }

    public function testValidServerToken()
    {
        $mailer = new SendGrid($this->adapter, 'username:password');
        $this->assertEquals('username:password', $mailer->getServerToken());
    }

    public function testSend()
    {
        $mailer = new SendGrid($this->adapter, 'username:password');
        $message = $this->getMessageMock(
            $from = 'hb@peytz.dk', $to = 'henrik@bjrnskov.dk', $subject = 'subject', $html = 'html', $text = 'text', $headers = array('X-Header' => 'Value')
        );

        $adapter = $mailer->getAdapter();
        $adapter
            ->expects($this->once())
            ->method('send')
            ->with(
                $this->equalTo($mailer->getEndpoint()),
                $this->equalTo('to=henrik%40bjrnskov.dk&from=hb%40peytz.dk&subject=subject&text=text&html=html&headers=%7B%22X-Header%22%3A%22Value%22%7D'),
                $this->equalTo(array(
                    'Content-Type' => 'multipart/form-data',
                ))
            )
            ->will($this->returnValue(new Response(200, '')))
        ;

        $mailer->send($message);
    }

    public function test400ResponseFails()
    {

        $mailer = new SendGrid($this->adapter, 'username:password');
        $adapter = $this->adapter;

        $adapter
            ->expects($this->any())
            ->method('send')
            ->will($this->returnValue(new Response(400, '')))
        ;

        $this->setExpectedException('Stampie\Exception\ApiException');

        $mailer->send($this->getMessageMock('hb@peytz.dk', 'henrik@bjrnskov.dk', 'subject', 'html'));
    }

    /**
     * @dataProvider getValuesFor500Fails
     */
    public function test500ReponseFails($statusCode, $statusText)
    {
        $mailer = new SendGrid($this->adapter, 'username:password');
        $adapter = $this->adapter;

        $adapter
            ->expects($this->any())
            ->method('send')
            ->will($this->returnValue(new Response($statusCode, '')))
        ;

        $this->setExpectedException('Stampie\Exception\HttpException', $statusText);

        $mailer->send($this->getMessageMock('hb@peytz.dk', 'henrik@bjrnskov.dk', 'subject', 'html'));

    }

    public function getValuesFor500Fails()
    {
        return array(
            array(500, 'Internal Server Error'),
            array(501, 'Not Implemented'),
            array(502, 'Bad Gateway'),
            array(503, 'Service Unavailable'),
            array(504, 'Gateway Timeout'),
            array(505, 'HTTP Version Not Supported'),
        );
    }
}
