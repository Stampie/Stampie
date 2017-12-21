<?php

namespace Stampie\Tests\Mailer;

use Http\Client\HttpClient;
use Stampie\Adapter\Response;
use Stampie\Adapter\ResponseInterface;
use Stampie\Identity;
use Stampie\Mailer\Mandrill;
use Stampie\MessageInterface;
use Stampie\Tests\TestCase;

class MandrillTest extends TestCase
{
    const SERVER_TOKEN = '5daa75d9-8fad-4211-9b18-49124642732e';

    /**
     * @var Mandrill
     */
    private $mailer;

    /**
     * @var HttpClient|\PHPUnit_Framework_MockObject_MockObject
     */
    private $httpClient;

    public function setUp()
    {
        $this->httpClient = $this->getMockBuilder(HttpClient::class)->getMock();
        $this->mailer = new TestMandrill(
            $this->httpClient,
            self::SERVER_TOKEN
        );
    }

    public function testEndpoint()
    {
        $this->assertEquals('https://mandrillapp.com/api/1.0/messages/send.json', $this->mailer->getEndpoint());
    }

    public function testHeaders()
    {
        $this->assertEquals([
            'Content-Type' => 'application/json',
        ], $this->mailer->getHeaders());
    }

    public function testFormat()
    {
        $message = $this->getMessageMock(
            $from = 'hb@peytz.dk',
            $to = 'henrik@bjrnskov.dk',
            $subject = 'Stampie is awesome',
            $html = 'So what do you thing'
        );

        $this->assertEquals(json_encode([
            'key'     => self::SERVER_TOKEN,
            'message' => [
                'from_email' => $from,
                'to'         => [['email' => $to, 'name' => null, 'type' => 'to']],
                'subject'    => $subject,
                'html'       => $html,
            ],
        ]), $this->mailer->format($message));
    }

    public function testFormatTaggable()
    {
        $message = $this->getTaggableMessageMock(
            $from = 'hb@peytz.dk',
            $to = 'henrik@bjrnskov.dk',
            $subject = 'Stampie is awesome',
            $html = 'So what do you thing',
            $text = 'text',
            $headers = ['X-Stampie-To' => 'henrik+to@bjrnskov.dk'],
            $tag = 'tag'
        );

        $this->assertEquals(json_encode([
            'key'     => self::SERVER_TOKEN,
            'message' => [
                'from_email' => $from,
                'to'         => [['email' => $to, 'name' => null, 'type' => 'to']],
                'subject'    => $subject,
                'headers'    => $headers,
                'text'       => $text,
                'html'       => $html,
                'tags'       => [$tag],
            ],
        ]), $this->mailer->format($message));
    }

    public function testFormatMetadataAware()
    {
        $message = $this->getMetadataAwareMessageMock(
            $from = 'hb@peytz.dk',
            $to = 'henrik@bjrnskov.dk',
            $subject = 'Stampie is awesome',
            $html = 'So what do you thing',
            $text = 'text',
            $headers = ['X-Stampie-To' => 'henrik+to@bjrnskov.dk'],
            $metadata = ['client_name' => 'Stampie']
        );

        $this->assertEquals(json_encode([
            'key'     => self::SERVER_TOKEN,
            'message' => [
                'from_email' => $from,
                'to'         => [['email' => $to, 'name' => null, 'type' => 'to']],
                'subject'    => $subject,
                'headers'    => $headers,
                'text'       => $text,
                'html'       => $html,
                'metadata'   => $metadata,
            ],
        ]), $this->mailer->format($message));
    }

    public function testFormatAttachments()
    {
        $this->mailer = $this
                            ->getMockBuilder(__NAMESPACE__.'\\TestMandrill')
                            ->setConstructorArgs([$this->httpClient, self::SERVER_TOKEN])
                            ->setMethods(['getAttachmentContent'])
                            ->getMock();

        $contentCallback = function ($attachment) {
            return 'content:'.$attachment->getPath();
        };

        $this->mailer
            ->expects($this->atLeastOnce())
            ->method('getAttachmentContent')
            ->will($this->returnCallback($contentCallback));

        $message = $this->getAttachmentsMessageMock(
            $from = 'hb@peytz.dk',
            $to = 'henrik@bjrnskov.dk',
            $subject = 'Stampie is awesome',
            $html = 'So what do you thing',
            $text = 'text',
            $headers = ['X-Stampie-To' => 'henrik+to@bjrnskov.dk'],
            array_merge(
                $attachments = [
                    $this->getAttachmentMock('files/image-1.jpg', 'file1.jpg', 'image/jpeg', null),
                    $this->getAttachmentMock('files/image-2.jpg', 'file2.jpg', 'image/jpeg', null),
                ],
                $images = [
                    $this->getAttachmentMock('files/image-3.jpg', 'file3.jpg', 'image/jpeg', 'contentid1'),
                ]
            )
        );

        $processedAttachments = [];
        foreach ($attachments as $attachment) {
            $processedAttachments[] = [
                'type'    => $attachment->getType(),
                'name'    => $attachment->getName(),
                'content' => base64_encode($contentCallback($attachment)),
            ];
        }

        $processedImages = [];
        foreach ($images as $attachment) {
            $processedImages[] = [
                'type'    => $attachment->getType(),
                'name'    => $attachment->getId(),
                'content' => base64_encode($contentCallback($attachment)),
            ];
        }

        $this->assertEquals(json_encode([
            'key'     => self::SERVER_TOKEN,
            'message' => [
                'from_email'  => $from,
                'to'          => [['email' => $to, 'name' => null, 'type' => 'to']],
                'subject'     => $subject,
                'headers'     => $headers,
                'text'        => $text,
                'html'        => $html,
                'attachments' => $processedAttachments,
                'images'      => $processedImages,
            ],
        ]), $this->mailer->format($message));
    }

    public function testGetFiles()
    {
        $self = $this; // PHP5.3 compatibility
        $adapter = $this->httpClient;
        $token = self::SERVER_TOKEN;
        $buildMocks = function ($attachments, &$invoke) use ($self, $adapter, $token) {
            $mailer = $self->getMockBuilder('\\Stampie\\Mailer\\Mandrill')
                ->setConstructorArgs([$adapter, $token])
                ->getMock()
            ;

            // Wrap protected method with accessor
            $mirror = new \ReflectionClass($mailer);
            $method = $mirror->getMethod('getFiles');
            $method->setAccessible(true);

            $invoke = function () use ($mailer, $method) {
                $args = func_get_args();
                array_unshift($args, $mailer);

                return call_user_func_array([$method, 'invoke'], $args);
            };

            $message = $self->getAttachmentsMessageMock('test@example.com', 'other@example.com', 'Subject', null, null, [], $attachments);

            return [$mailer, $message];
        };

        // Actual tests

        $attachments = [
            $this->getAttachmentMock('path-1.txt', 'path1.txt', 'text/plain', null),
        ];

        list($mailer, $message) = $buildMocks($attachments, $invoke);

        $this->assertEquals([], $invoke($message), 'Attachments should never be returned separately from body');
    }

    /**
     * @dataProvider carbonCopyProvider
     */
    public function testFormatCarbonCopy($recipient, $ccs, $expectedTos)
    {
        $message = $this->getCarbonCopyMock(
            $from = 'hb@peytz.dk',
            $to = $recipient,
            $subject = 'Stampie is awesome, right?',
            $html = 'So what do you think',
            $text = 'text',
            $headers = ['X-Stampie-To' => 'henrik+to@bjrnskov.dk'],
            $cc = $ccs
        );

        $this->assertEquals(json_encode([
            'key'     => self::SERVER_TOKEN,
            'message' => [
                'from_email' => $from,
                'to'         => $expectedTos,
                'subject'    => $subject,
                'headers'    => $headers,
                'text'       => $text,
                'html'       => $html,
            ],
        ]), $this->mailer->format($message));
    }

    /**
     * @dataProvider blindCarbonCopyProvider
     */
    public function testFormatBlindCarbonCopy($recipient, $bccs, $expectedTos)
    {
        $message = $this->getBlindCarbonCopyMock(
            $from = 'hb@peytz.dk',
            $to = $recipient,
            $subject = 'Stampie is awesome, right?',
            $html = 'So what do you think',
            $text = 'text',
            $headers = ['X-Stampie-To' => 'henrik+to@bjrnskov.dk'],
            $bcc = $bccs
        );

        $this->assertEquals(json_encode([
            'key'     => self::SERVER_TOKEN,
            'message' => [
                'from_email' => $from,
                'to'         => $expectedTos,
                'subject'    => $subject,
                'headers'    => $headers,
                'text'       => $text,
                'html'       => $html,
            ],
        ]), $this->mailer->format($message));
    }

    /**
     * @dataProvider handleDataProvider
     */
    public function testHandle($statusCode, $content)
    {
        $response = new Response($statusCode, json_encode(['message' => $content, 'code' => -1]));

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
        return [
            [400, 'Bad Request'],
            [401, 'Unauthorized'],
            [504, 'Gateway Timeout'],
        ];
    }

    public function blindCarbonCopyProvider()
    {
        return [
            ['henrik@bjrnskov.dk', 'gauthier.wallet@gmail.com', [
                ['email' => 'henrik@bjrnskov.dk', 'name' => null, 'type' => 'to'],
                ['email' => 'gauthier.wallet@gmail.com', 'name' => null, 'type' => 'bcc'],
            ]],
            [[new Identity('henrik@bjrnskov.dk')], 'gauthier.wallet@gmail.com', [
                ['email' => 'henrik@bjrnskov.dk', 'name' => null, 'type' => 'to'],
                ['email' => 'gauthier.wallet@gmail.com', 'name' => null, 'type' => 'bcc'],
            ]],
            [[new Identity('henrik@bjrnskov.dk'), new Identity('henrik2@bjrnskov.dk')], 'gauthier.wallet@gmail.com', [
                ['email' => 'henrik@bjrnskov.dk', 'name' => null, 'type' => 'to'],
                ['email' => 'henrik2@bjrnskov.dk', 'name' => null, 'type' => 'to'],
                ['email' => 'gauthier.wallet@gmail.com', 'name' => null, 'type' => 'bcc'],
            ]],
            [[new Identity('henrik@bjrnskov.dk')], [new Identity('gauthier.wallet@gmail.com')], [
                ['email' => 'henrik@bjrnskov.dk', 'name' => null, 'type' => 'to'],
                ['email' => 'gauthier.wallet@gmail.com', 'name' => null, 'type' => 'bcc'],
            ]],
            [[new Identity('henrik@bjrnskov.dk'), new Identity('henrik2@bjrnskov.dk')], [new Identity('gauthier.wallet@gmail.com'), new Identity('gauthier.wallet2@gmail.com')], [
                ['email' => 'henrik@bjrnskov.dk', 'name' => null, 'type' => 'to'],
                ['email' => 'henrik2@bjrnskov.dk', 'name' => null, 'type' => 'to'],
                ['email' => 'gauthier.wallet@gmail.com', 'name' => null, 'type' => 'bcc'],
                ['email' => 'gauthier.wallet2@gmail.com', 'name' => null, 'type' => 'bcc'],
            ]],
            ['henrik@bjrnskov.dk', [new Identity('gauthier.wallet@gmail.com'), new Identity('gauthier.wallet2@gmail.com')], [
                ['email' => 'henrik@bjrnskov.dk', 'name' => null, 'type' => 'to'],
                ['email' => 'gauthier.wallet@gmail.com', 'name' => null, 'type' => 'bcc'],
                ['email' => 'gauthier.wallet2@gmail.com', 'name' => null, 'type' => 'bcc'],
            ]],
        ];
    }

    public function carbonCopyProvider()
    {
        return [
            ['henrik@bjrnskov.dk', 'gauthier.wallet@gmail.com', [
                ['email' => 'henrik@bjrnskov.dk', 'name' => null, 'type' => 'to'],
                ['email' => 'gauthier.wallet@gmail.com', 'name' => null, 'type' => 'cc'],
            ]],
            [[new Identity('henrik@bjrnskov.dk')], 'gauthier.wallet@gmail.com', [
                ['email' => 'henrik@bjrnskov.dk', 'name' => null, 'type' => 'to'],
                ['email' => 'gauthier.wallet@gmail.com', 'name' => null, 'type' => 'cc'],
            ]],
            [[new Identity('henrik@bjrnskov.dk'), new Identity('henrik2@bjrnskov.dk')], 'gauthier.wallet@gmail.com', [
                ['email' => 'henrik@bjrnskov.dk', 'name' => null, 'type' => 'to'],
                ['email' => 'henrik2@bjrnskov.dk', 'name' => null, 'type' => 'to'],
                ['email' => 'gauthier.wallet@gmail.com', 'name' => null, 'type' => 'cc'],
            ]],
            [[new Identity('henrik@bjrnskov.dk')], [new Identity('gauthier.wallet@gmail.com')], [
                ['email' => 'henrik@bjrnskov.dk', 'name' => null, 'type' => 'to'],
                ['email' => 'gauthier.wallet@gmail.com', 'name' => null, 'type' => 'cc'],
            ]],
            [[new Identity('henrik@bjrnskov.dk'), new Identity('henrik2@bjrnskov.dk')], [new Identity('gauthier.wallet@gmail.com'), new Identity('gauthier.wallet2@gmail.com')], [
                ['email' => 'henrik@bjrnskov.dk', 'name' => null, 'type' => 'to'],
                ['email' => 'henrik2@bjrnskov.dk', 'name' => null, 'type' => 'to'],
                ['email' => 'gauthier.wallet@gmail.com', 'name' => null, 'type' => 'cc'],
                ['email' => 'gauthier.wallet2@gmail.com', 'name' => null, 'type' => 'cc'],
            ]],
            ['henrik@bjrnskov.dk', [new Identity('gauthier.wallet@gmail.com'), new Identity('gauthier.wallet2@gmail.com')], [
                ['email' => 'henrik@bjrnskov.dk', 'name' => null, 'type' => 'to'],
                ['email' => 'gauthier.wallet@gmail.com', 'name' => null, 'type' => 'cc'],
                ['email' => 'gauthier.wallet2@gmail.com', 'name' => null, 'type' => 'cc'],
            ]],
        ];
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
