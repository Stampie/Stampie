<?php

namespace Stampie\Tests\Mailer;

use Stampie\Mailer\SendGrid;
use Stampie\Adapter\Response;
use Stampie\Adapter\ResponseInterface;
use Stampie\MessageInterface;

class SendGridTest extends \Stampie\Tests\BaseMailerTest
{
    const SERVER_TOKEN = 'rudolph:rednose';

    public function setUp()
    {
        parent::setUp();

        $this->mailer = new TestSendGrid(
            $this->adapter,
            self::SERVER_TOKEN
        );
    }

    public function testInValidServerToken()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->mailer->setServerToken('invalid');
    }

    public function testEndpoint()
    {
        $this->assertEquals('https://sendgrid.com/api/mail.send.json', $this->mailer->getEndpoint());
    }

    /**
     * @dataProvider handleDataProvider
     */
    public function testHandle($statusCode, $content, $exceptionType)
    {
        $response = new Response($statusCode, $content);
        $this->setExpectedException($exceptionType);

        $this->mailer->handle($response);
    }

    public function testFormat()
    {
        $api_user = 'rudolph';
        $api_key = 'rednose';

        $message =  $this->getMessageMock(
            $from = 'henrik@bjrnskov.dk',
            $to = 'hb@peytz.dk',
            $subject = 'Trying out Stampie',
            $html = 'Stampie is Awesome',
            $text = '',
            $headers = array(
                'X-Custom-Header' => 'My Custom Header Value',
            )
        );

        $headers = json_encode($headers);

        $query = compact(
            'api_user', 'api_key', 'to', 'from', 'subject', 'html', 'headers'
        );


        $this->assertEquals(http_build_query(
            $query
        ), $this->mailer->format($message));
    }

    public function testFormatTaggable()
    {
        $api_user = 'rudolph';
        $api_key = 'rednose';

        $message =  $this->getTaggableMessageMock(
            $from = 'henrik@bjrnskov.dk',
            $to = 'hb@peytz.dk',
            $subject = 'Trying out Stampie',
            $html = 'Stampie is Awesome',
            $text = '',
            $headers = array(
                'X-Custom-Header' => 'My Custom Header Value',
            ),
            $tag = 'tag'
        );

        $headers = json_encode($headers);

        $query = compact(
            'api_user', 'api_key', 'to', 'from', 'subject', 'html', 'headers'
        );
        $query['x-smtpapi']['category'] = array($tag);


        $this->assertEquals(http_build_query(
            $query
        ), $this->mailer->format($message));
    }

    public function handleDataProvider()
    {
        return array(
            array(400, '{ "errors" : ["Error In an Array"] }', 'Stampie\Exception\ApiException'),
            array(500, '', 'Stampie\Exception\HttpException')
        );
    }
}

class TestSendGrid extends SendGrid
{
    public function getEndpoint()
    {
        return parent::getEndpoint();
    }

    public function handle(ResponseInterface $response)
    {
        parent::handle($response);
    }

    public function format(MessageInterface $message)
    {
        return parent::format($message);
    }
}
