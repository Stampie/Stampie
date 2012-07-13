<?php

namespace Stampie\Tests\Mailer;

use Stampie\Mailer\Mandrill;
use Stampie\Adapter\Response;
use Stampie\Adapter\ResponseInterface;
use Stampie\MessageInterface;

class MandrillTest extends \Stampie\Tests\BaseMailerTest
{
    const SERVER_TOKEN = '5daa75d9-8fad-4211-9b18-49124642732e';

    public function setUp()
    {
        parent::setUp();

        $this->mailer = new TestMandrill(
            $this->adapter,
            self::SERVER_TOKEN
        );
    }

    public function testEndpoint()
    {
        $this->assertEquals('https://mandrillapp.com/api/1.0/messages/send.json', $this->mailer->getEndpoint());
    }

    public function testHeaders()
    {
        $this->assertEquals(array(
            'Content-Type' => 'application/json',
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
            'key' => self::SERVER_TOKEN,
            'message' => array(
                'from_email' => $from,
                'to' => array(array('email' => $to)),
                'subject' => $subject,
                'html' => $html,
            ),
        )), $this->mailer->format($message));
    }

    /**
     * @dataProvider handleDataProvider
     */
    public function testHandle($statusCode, $content)
    {
        $response = new Response($statusCode, json_encode(array('message' => $content, 'code' => -1)));

        try {
            $this->mailer->handle($response);
        } catch (\Stampie\Exception\ApiException $e) {
            $this->assertInstanceOf('Stampie\Exception\HttpException', $e->getPrevious());
            $this->assertEquals($e->getPrevious()->getMessage(), $content);
            $this->assertEquals($e->getMessage(), $content);
            return;
        }

        $this->fail('Expected Stampie\Exception\ApiException to be trown');
    }

    public function handleDataProvider()
    {
        return array(
            array(400, 'Bad Request'),
            array(401, 'Unauthorized'),
            array(504, 'Gateway Timeout'),
        );
    }
}

class TestMandrill extends Mandrill
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
