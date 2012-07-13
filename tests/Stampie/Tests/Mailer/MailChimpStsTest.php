<?php

namespace Stampie\Tests\Mailer;

use Stampie\Tests\BaseMailerTest;
use Stampie\Mailer\MailChimpSts;
use Stampie\Adapter\Response;
use Stampie\Adapter\ResponseInterface;
use Stampie\MessageInterface;

class MailChimpStsTest extends BaseMailerTest
{
    const SERVER_TOKEN = '21381c475f2bccabdc860aa6dbe1c362d48688d7-us4';

    public function setUp()
    {
        parent::setUp();

        $this->mailer = new TestMailChimpSts(
            $this->adapter,
            self::SERVER_TOKEN
        );
    }

    /**
     * @dataProvider endpointDataProvider
     */
    public function testEndpoint($serverToken)
    {
        list(, $dc) = explode('-', $serverToken);
        $this->mailer->setServerToken($serverToken);
        $this->assertEquals('http://' . $dc . '.sts.mailchimp.com/1.0/SendEmail.json', $this->mailer->getEndpoint());
    }

    public function testFormat()
    {
        $message = $this->getMessageMock(
            $from = 'henrik@bjrnskov.dk',
            $to = 'hb@peytz.dk',
            $subject = 'Stampie is awesome',
            $html = 'asdad',
            $text = ''
        );

        $this->assertEquals(http_build_query(array(
            'apikey' => self::SERVER_TOKEN,
            'message' => array(
                'html' => $html,
                'subject' => $subject,
                'to_email' => $to,
                'from_email' => $from,
            ),
        )), $this->mailer->format($message));
    }

    /**
     * @dataProvider handleDataProvider
     */
    public function testHandle($statusCode, $content)
    {
        $response = new Response($statusCode, json_encode(array('message' => $content)));

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

    public function endpointDataProvider()
    {
        return array(
           array('82ce197df3e18234bc1535ccf5780f9f3cc79f92-uk1'),
           array('4cbd1e10f62a197d8397df258b04842ba10fced7-us3'),
           array('e02c993a61d86419f0e5ce8ee01a6e1373bb5623-us6'),
        );
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

class TestMailChimpSts extends MailChimpSts
{
    public function getEndpoint()
    {
        return parent::getEndpoint();
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
