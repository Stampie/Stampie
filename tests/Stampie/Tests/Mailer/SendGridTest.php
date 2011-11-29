<?php

namespace Stampie\Tests\Mailer;

use Stampie\Mailer\SendGrid;
use Stampie\Adapter\Response;

class SendGridTest extends \PHPUnit_Framework_TestCase
{
    public function testServerTokenMissingDelimiter()
    {
        $this->setExpectedException('InvalidArgumentException', 'SendGrid uses a "username:password" based ServerToken');
        $mailer = new SendGrid($this->getAdapterMock(), '');
    }

    public function testValidServerToken()
    {
        $mailer = new SendGrid($this->getAdapterMock(), 'username:password');
        $this->assertEquals('username:password', $mailer->getServerToken());
    }

    public function testSend()
    {
        $mailer = new SendGrid($this->getAdapterMock(), 'username:password');
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

    protected function getAdapterMock()
    {
        return $this->getMock('Stampie\Adapter\AdapterInterface');
    }

    protected function getMessageMock($from, $to, $subject, $html = null, $text = null, array $headers = array())
    {
        $message = $this->getMock('Stampie\MessageInterface');
        $message
            ->expects($this->any())
            ->method('getFrom')
            ->will($this->returnValue($from))
        ;

        $message
            ->expects($this->any())
            ->method('getTo')
            ->will($this->returnValue($to))
        ;

        $message
            ->expects($this->any())
            ->method('getSubject')
            ->will($this->returnValue($subject))
        ;

        $message
            ->expects($this->any())
            ->method('getHtml')
            ->will($this->returnValue($html))
        ;

        $message
            ->expects($this->any())
            ->method('getText')
            ->will($this->returnValue($text))
        ;

        $message
            ->expects($this->any())
            ->method('getHeaders')
            ->will($this->returnValue($headers))
        ;

        return $message;
    }
}
