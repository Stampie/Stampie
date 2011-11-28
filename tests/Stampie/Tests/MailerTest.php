<?php

namespace Stampie\Tests;

use Stampie\Mailer;

class MailerTest extends \PHPUnit_Framework_TestCase
{
    const SERVER_TOKEN = "mySuperSecretServerToken";

    public function setUp()
    {
        $this->buzz = $this->getMock('Buzz\Browser');
    }

    public function testSettersAndGetters()
    {
        $mailer = new Mailer($this->buzz);
        $this->assertEquals($this->buzz, $mailer->getBrowser());
        $this->assertEquals("POSTMARK_API_TEST", $mailer->getServerToken());

        $mailer->setServerToken(static::SERVER_TOKEN, $mailer->getServerToken());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testServerTokenCannotBeEmpty()
    {
        $mailer = new Mailer($this->buzz);
        $mailer->setServerToken('');
    }

    public function testSendWithTextAndOrHtmlIsSuccessful()
    {
        $message = $this->getMessageMock(
            $from = 'hb@peytz.dk', $to = 'henrik@bjrnskov.dk', $subject = 'Subject',
            $html = 'html', $text = 'text', $headers = array()
        );

        $mailer  = new Mailer($this->buzz, 'mySecretToken');

        $this
            ->buzz
            ->expects($this->once())
            ->method('post')
            ->with($this->equalTo(Mailer::ENDPOINT), $this->equalTo(array(
                'Content-Type: application/json',
                'X-Postmark-Server-Token: ' . $mailer->getServerToken(),
            )), json_encode(array(
                'From'     => $from,
                'To'       => $to,
                'Subject'  => $subject,
                'TextBody' => $text,
                'HtmlBody' => $html,
            )))
            ->will($this->returnValue($this->getResponseMock(200, array())))
        ;

        $this->assertTrue($mailer->send($message));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSendWithEmptyTextAndHtml()
    {
        $message = $this->getMessageMock('hb@peytz.dk', 'henrik@bjrnskov.dk', 'Sample Subject');
        $mailer = new Mailer($this->buzz);
        $mailer->send($message);
    }

    /**
     * @dataProvider getHttpProviderValues
     */
    public function testHttpErrorCodeResponse($statusCode, $description)
    {
        $mailer = new Mailer($this->buzz);

        $this
            ->buzz
            ->expects($this->any())
            ->method('post')
            ->will($this->returnValue($this->getResponseMock($statusCode, array())))
        ;

        $this->setExpectedException('LogicException', $description);

        $mailer->send($this->getMessageMock('hb@peytz', 'henrik@bjrnskov', 'subject', 'html'));
    }

    /**
     * @dataProvider getApiProviderValues
     */
    public function testApiErrorCodeResponse($statusCode, $description)
    {
        $mailer = new Mailer($this->buzz);

        $this
            ->buzz
            ->expects($this->any())
            ->method('post')
            ->will($this->returnValue($this->getResponseMock(422, array(
                'ErrorCode' => $statusCode,
                'Message' => $description,
            ))))
        ;

        $this->setExpectedException('LogicException', $description);

        $mailer->send($this->getMessageMock('hb@peytz', 'henrik@bjrnskov', 'subject', 'html'));
    }

    public function getHttpProviderValues()
    {
        return array(
            array(401, 'Unauthorized'),
            array(500, 'Internal Server Error'),
        );
    }

    public function getApiProviderValues()
    {
        return array(
            array(0, 'Bad or Missing API Token'),
            array(300, 'Invalid email request'),
            array(400, 'Sender signature not confirmed')
        );
    }

    protected function getResponseMock($statusCode, array $content)
    {
        $response = $this->getMock('Buzz\Message\Response');
        $response
            ->expects($this->any())
            ->method('getStatusCode')
            ->will($this->returnValue($statusCode))
        ;

        $response
            ->expects($this->any())
            ->method('getContent')
            ->will($this->returnValue(json_encode($content)))
        ;

        return $response;
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
