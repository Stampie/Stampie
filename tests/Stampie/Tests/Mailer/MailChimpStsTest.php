<?php

namespace Stampie\Tests\Mailer;

use Stampie\Tests\BaseMailerTest;
use Stampie\Mailer\MailChimpSts;
use Stampie\Adapter\Response;

class MailChimpStsTest extends BaseMailerTest
{
    public function setUp()
    {
        parent::setUp();

        $this->mailer = new MailChimpSts($this->adapter, 'Token-uk2');
    }

    public function getEndpointValues()
    {
        return array(
            array('s2038293723-uk', 'uk'),
            array('dk', 'dk'),
            array('asdq372923', 'asdq372923'),
            array('one-two-three', 'three'),
        );
    }

    public function testSendSuccess()
    {
        $message = $this->getMessageMock('henrik@bjrnskov', 'henrik@bearwoods.dk', 'content');
        $mailer = $this->mailer;
        $adapter = $this->adapter;
        $adapter
            ->expects($this->once())
            ->method('send')
            ->with(
                $this->equalTo($mailer->getEndpoint()),
                $this->equalTo(http_build_query(array(
                    'apikey' => $mailer->getServerToken(),
                    'message' => array_filter(array(
                        'html' => $message->getHtml(),
                        'text' => $message->getText(),
                        'subject' => $message->getSubject(),
                        'to_email' => $message->getTo(),
                        'from_email' => $message->getFrom(),
                    )),
                ))),
                $this->equalTo(array())
            )
            ->will(
                $this->returnValue(new Response(200, ''))
            )
        ;

        $mailer->send($message);
    }

    /**
     * @dataProvider getExceptionErrorCodes
     */
    public function testSendThrowsApiException($code, $messageText)
    {

        $message = $this->getMessageMock('henrik@bjrnskov', 'henrik@bearwoods.dk', 'content');
        $mailer = $this->mailer;
        $adapter = $this->adapter;
        $adapter
            ->expects($this->once())
            ->method('send')
            ->with(
                $this->equalTo($mailer->getEndpoint()),
                $this->equalTo(http_build_query(array(
                    'apikey' => $mailer->getServerToken(),
                    'message' => array_filter(array(
                        'html' => $message->getHtml(),
                        'text' => $message->getText(),
                        'subject' => $message->getSubject(),
                        'to_email' => $message->getTo(),
                        'from_email' => $message->getFrom(),
                    )),
                ))),
                $this->equalTo(array())
            )
            ->will(
                $this->returnValue(new Response($code, '{ "message" : "' . $messageText . '"}'))
            )
        ;

        $this->setExpectedException('Stampie\Exception\ApiException', $messageText);

        $mailer->send($message);
    }

    public function getExceptionErrorCodes()
    {
        return array(
            array(400, 'Something is wrong'),
            array(500, 'Something is wrong'),
            array(404, 'Not found'),
        );
    }

    /**
     * @dataProvider getEndpointValues
     */
    public function testGetEndPointSplitsServerToken($serverToken, $initials)
    {
        $this->mailer->setServerToken($serverToken);
        $this->assertEquals('http://' . $initials . '.sts.mailchimp.com/1.0/SendEmail.json', $this->mailer->getEndpoint());
    }
}
