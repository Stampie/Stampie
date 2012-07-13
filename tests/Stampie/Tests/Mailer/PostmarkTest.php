<?php

namespace Stampie\Tests\Mailer;

use Stampie\Mailer\Postmark;
use Stampie\Adapter\Response;
use Stampie\Adapter\ResponseInterface;
use Stampie\MessageInterface;

class PostmarkTest extends \Stampie\Tests\BaseMailerTest
{
    const SERVER_TOKEN = '5daa75d9-8fad-4211-9b18-49124642732e';

    public function setUp()
    {
        parent::setUp();

        $this->mailer = new TestPostmark(
            $this->adapter,
            self::SERVER_TOKEN
        );
    }

    public function testEndpoint()
    {
        $this->assertEquals('http://api.postmarkapp.com/email', $this->mailer->getEndpoint());
    }

    public function testHeaders()
    {
        $this->assertEquals(array(
            'Content-Type' => 'application/json',
            'X-Postmark-Server-Token' => $this->mailer->getServerToken(),
            'Accept' => 'application/json',
        ), $this->mailer->getHeaders());
    }

    public function testFormat()
    {
        $message = $this->getMessageMock(
            $from = 'hb@peytz.dk',
            $to = 'henrik@bjrnskov.dk',
            $subject = 'Stampie is awesome',
            $html = 'So what do you thing'
        );

        $this->assertEquals(json_encode(array(
            'From' => $from,
            'To' => $to,
            'Subject' => $subject,
            'HtmlBody' => $html,
        )), $this->mailer->format($message));
    }

    /**
     * @dataProvider handleDataProvider
     */
    public function testHandle($statusCode, $content, $exceptionType, $exceptionMessage)
    {
        $response = new Response($statusCode, $content);

        $this->setExpectedException($exceptionType, $exceptionMessage);

        $this->mailer->handle($response);
    }

    public function handleDataProvider()
    {
        return array(
            array(500, '', 'Stampie\Exception\HttpException', 'Internal Server Error'),
            array(400, '', 'Stampie\Exception\HttpException', 'Bad Request'),
            array(422, '{ "Message" : "Bad Credentials" }', 'Stampie\Exception\ApiException', 'Bad Credentials'),
        );
    }
}

class TestPostmark extends Postmark
{
    public function getEndpoint()
    {
        return parent::getEndpoint();
    }

    public function handle(ResponseInterface $response)
    {
        parent::handle($response);
    }

    public function getHeaders()
    {
        return parent::getHeaders();
    }

    public function format(MessageInterface $message)
    {
        return parent::format($message);
    }
}
