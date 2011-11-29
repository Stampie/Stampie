<?php

namespace Stampie\Tests\Mailer;

use Stampie\Mailer\Postmark;

class PostmarkTest extends \PHPUnit_Framework_TestCase
{
    const SERVER_TOKEN = "mySuperSecretServerToken";

    public function setUp()
    {
        $this->adapter = $this->getMock('Stampie\Adapter\AdapterInterface');
    }

    public function testSettersAndGetters()
    {
        $mailer = new Postmark($this->adapter, self::SERVER_TOKEN);
        $this->assertEquals($this->adapter, $mailer->getAdapter());
        $this->assertEquals(self::SERVER_TOKEN, $mailer->getServerToken());
        $this->assertEquals('http://api.postmarkapp.com/email', $mailer->getEndpoint());
    }


    /**
     * @expectedException InvalidArgumentException
     */
    public function testServerTokenCannotBeEmpty()
    {
        $mailer = new Postmark($this->adapter, '');
    }

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
            ->with($this->equalTo($json), $this->equalTo($headers))
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
        $mailer = new Postmark($this->adapter, self::SERVER_TOKEN);
        $mailer->send($message);
    }

    /**
     * @dataProvider getHttpProviderValues
     */
    public function testHttpErrorCodeResponse($statusCode, $description)
    {
        $mailer = new Postmark($this->adapter, self::SERVER_TOKEN);

        $this
            ->adapter
            ->expects($this->any())
            ->method('send')
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
        $mailer = new Postmark($this->adapter, self::SERVER_TOKEN);

        $this
            ->adapter
            ->expects($this->any())
            ->method('send')
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
        $response = $this->getMock('Stampie\Adapter\ResponseInterface');
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
