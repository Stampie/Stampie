<?php

namespace Stampie\Tests\Mailer;

use Stampie\Mailer\Postmark;
use Stampie\Adapter\Response;

class PostmarkTest extends \Stampie\Tests\BaseMailerTest
{
    const SERVER_TOKEN = "mySuperSecretServerToken";

    public function testSendWithTextAndOrHtmlIsSuccessful()
    {
        $mailer  = new Postmark($this->adapter, self::SERVER_TOKEN);
        $message = $this->getMessageMock(
            $from = 'hb@peytz.dk', $to = 'henrik@bjrnskov.dk', $subject = 'Subject',
            $html = 'html', $text = 'text', $headers = array()
        );

        $headers = array(
            'Content-Type' => 'application/json',
            'X-Postmark-Server-Token' => $mailer->getServerToken(),
        );

        $json = json_encode(array(
            'From'     => $from,
            'To'       => $to,
            'Subject'  => $subject,
            'TextBody' => $text,
            'HtmlBody' => $html,
        ));

        $this
            ->adapter
            ->expects($this->once())
            ->method('send')
            ->with(
                $this->equalTo($mailer->getEndpoint()),
                $this->equalTo($json),
                $this->equalTo($headers)
            )
            ->will($this->returnValue(new Response(200, '')))
        ;

        $this->assertTrue($mailer->send($message));
    }

    public function testUnprocessableEntitySend()
    {
        $mailer = new Postmark($this->adapter, self::SERVER_TOKEN);
        $adapter = $this->adapter;

        $adapter
            ->expects($this->any())
            ->method('send')
            ->will($this->returnValue(new Response(422, '{ "ErrorCode" : 0, "Message" : "Invalid API"}')))
        ;

        $this->setExpectedException('Stampie\Exception\ApiException', 'Invalid API');

        $mailer->send($this->getMessageMock('hb@peytz.dk', 'henrik@bjrnskov.dk', 'subject', 'html'));
        
    }

    public function testErrorHttpCodeSend()
    {
        $mailer = new Postmark($this->adapter, self::SERVER_TOKEN);
        $adapter = $this->adapter;

        $adapter
            ->expects($this->any())
            ->method('send')
            ->will($this->returnValue(new Response(500, '')))
        ;

        $this->setExpectedException('Stampie\Exception\HttpException', 'Internal Server Error');

        $mailer->send($this->getMessageMock('hb@peytz.dk', 'henrik@bjrnskov.dk', 'subject', 'html'));
        
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSendWithEmptyTextAndHtml()
    {
        $message = $this->getMessageMock('hb@peytz.dk', 'henrik@bjrnskov.dk', 'Sample Subject');
        $mailer = new Postmark($this->adapter, self::SERVER_TOKEN);
        $mailer->send($message);
    }
}
