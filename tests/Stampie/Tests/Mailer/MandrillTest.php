<?php

namespace Stampie\Tests\Mailer;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Http\Client\HttpClient;
use Stampie\Identity;
use Stampie\Mailer\Mandrill;
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
        $this->mailer = new Mandrill($this->httpClient, self::SERVER_TOKEN);
    }

    public function testSend()
    {
        $message = $this->getMessageMock('bob@example.com', 'alice@example.com', 'Stampie is awesome!', 'Trying out Stampie', null, [
            'X-Custom-Header' => 'My Custom Header Value',
        ]);

        $this->httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (Request $request) {
                $body = json_decode((string) $request->getBody(), true);

                return
                    $request->getMethod() === 'POST'
                    && (string) $request->getUri() === 'https://mandrillapp.com/api/1.0/messages/send.json'
                    && $request->getHeaderLine('Content-Type') === 'application/json'
                    && $body == [
                        'key' => self::SERVER_TOKEN,
                        'message' => [
                            'from_email' => 'bob@example.com',
                            'to' => [
                                [
                                    'email' => 'alice@example.com',
                                    'name' => null,
                                    'type' => 'to',
                                ],
                            ],
                            'subject' => 'Stampie is awesome!',
                            'html' => 'Trying out Stampie',
                            'headers' => [
                                'X-Custom-Header' => 'My Custom Header Value',
                            ],
                        ],
                    ];
            }))
            ->willReturn(new Response());

        $this->mailer->send($message);
    }

    public function testSendTaggable()
    {
        $message = $this->getTaggableMessageMock('bob@example.com', 'alice@example.com', 'Stampie is awesome', null, null, [], ['tag']);

        $this->httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (Request $request) {
                $body = json_decode((string) $request->getBody(), true);

                return $body == [
                    'key' => self::SERVER_TOKEN,
                    'message' => [
                        'from_email' => 'bob@example.com',
                        'to' => [
                            [
                                'email' => 'alice@example.com',
                                'name' => null,
                                'type' => 'to',
                            ],
                        ],
                        'subject' => 'Stampie is awesome',
                        'tags' => [
                            'tag',
                        ],
                    ],
                ];
            }))
            ->willReturn(new Response());

        $this->mailer->send($message);
    }

    public function testSendMetadataAware()
    {
        $message = $this->getMetadataAwareMessageMock('bob@example.com', 'alice@example.com', 'Stampie is awesome', null, null, [], [
            'client_name' => 'Stampie',
        ]);

        $this->httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (Request $request) {
                $body = json_decode((string) $request->getBody(), true);

                return $body == [
                    'key' => self::SERVER_TOKEN,
                    'message' => [
                        'from_email' => 'bob@example.com',
                        'to' => [
                            [
                                'email' => 'alice@example.com',
                                'name' => null,
                                'type' => 'to',
                            ],
                        ],
                        'subject' => 'Stampie is awesome',
                        'metadata' => [
                            'client_name' => 'Stampie',
                        ],
                    ],
                ];
            }))
            ->willReturn(new Response());

        $this->mailer->send($message);
    }

    public function testSendWithAttachments()
    {
        $message = $this->getAttachmentsMessageMock('bob@example.com', 'alice@example.com', 'Stampie is awesome', null, null, [], [
            $this->getAttachmentMock('path-1.txt', 'path1.txt', 'text/plain', null),
            $this->getAttachmentMock('path-2.txt', 'path2.txt', 'text/plain', null),
            $this->getAttachmentMock('logo.png', 'logo.png', 'image/png', 'contentid1'),
        ]);

        $this->httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (Request $request) {
                $body = json_decode((string) $request->getBody(), true);

                return $body == [
                    'key' => self::SERVER_TOKEN,
                    'message' => [
                        'from_email' => 'bob@example.com',
                        'to' => [
                            [
                                'email' => 'alice@example.com',
                                'name' => null,
                                'type' => 'to',
                            ],
                        ],
                        'subject' => 'Stampie is awesome',
                        'attachments' => [
                            [
                                'type' => 'text/plain',
                                'name' => 'path1.txt',
                                'content' => base64_encode(file_get_contents(__DIR__.'/../../../Fixtures/path-1.txt')),
                            ],
                            [
                                'type' => 'text/plain',
                                'name' => 'path2.txt',
                                'content' => base64_encode(file_get_contents(__DIR__.'/../../../Fixtures/path-2.txt')),
                            ],
                        ],
                        'images' => [
                            [
                                'type' => 'image/png',
                                'name' => 'contentid1',
                                'content' => base64_encode(file_get_contents(__DIR__.'/../../../Fixtures/logo.png')),
                            ],
                        ],
                    ],
                ];
            }))
            ->willReturn(new Response());

        $this->mailer->send($message);
    }

    public function testSendWithSubaccount()
    {
        $message = $this->getMessageMock('bob@example.com', 'alice@example.com', 'Stampie is awesome!');

        $this->httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (Request $request) {
                $body = json_decode((string) $request->getBody(), true);

                return $body['message']['subaccount'] === 'sub';
            }))
            ->willReturn(new Response());

        $this->mailer->setSubaccount('sub');
        $this->mailer->send($message);
    }

    /**
     * @dataProvider carbonCopyProvider
     *
     * @param string $recipient
     * @param array  $ccs
     * @param array  $expectedTos
     */
    public function testFormatCarbonCopy($recipient, $ccs, $expectedTos)
    {
        $message = $this->getCarbonCopyMock('bob@example.com', $recipient, 'Stampie is awesome', null, null, [], $ccs);

        $this->httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (Request $request) use ($expectedTos) {
                $body = json_decode((string) $request->getBody(), true);

                return $body == [
                    'key' => self::SERVER_TOKEN,
                    'message' => [
                        'from_email' => 'bob@example.com',
                        'to' => $expectedTos,
                        'subject' => 'Stampie is awesome',
                    ],
                ];
            }))
            ->willReturn(new Response());

        $this->mailer->send($message);
    }

    /**
     * @dataProvider blindCarbonCopyProvider
     *
     * @param string $recipient
     * @param array  $bccs
     * @param array  $expectedTos
     */
    public function testFormatBlindCarbonCopy($recipient, $bccs, $expectedTos)
    {
        $message = $this->getBlindCarbonCopyMock('bob@example.com', $recipient, 'Stampie is awesome', null, null, [], $bccs);

        $this->httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (Request $request) use ($expectedTos) {
                $body = json_decode((string) $request->getBody(), true);

                return $body == [
                    'key' => self::SERVER_TOKEN,
                    'message' => [
                        'from_email' => 'bob@example.com',
                        'to' => $expectedTos,
                        'subject' => 'Stampie is awesome',
                    ],
                ];
            }))
            ->willReturn(new Response());

        $this->mailer->send($message);
    }

    /**
     * @expectedException \Stampie\Exception\ApiException
     * @expectedExceptionMessage Bad Request
     */
    public function testHandleBadRequest()
    {
        $response = new Response(400, [], json_encode(['message' => 'Bad Request', 'code' => -1]));

        $message = $this->getMessageMock('bob@example.com', 'alice@example.com', 'Stampie is awesome!');

        $this->httpClient
            ->method('sendRequest')
            ->willReturn($response);

        $this->mailer->send($message);
    }

    /**
     * @expectedException \Stampie\Exception\ApiException
     * @expectedExceptionMessage Unauthorized
     */
    public function testHandleUnauthorized()
    {
        $response = new Response(401, [], json_encode(['message' => 'Unauthorized', 'code' => -1]));

        $message = $this->getMessageMock('bob@example.com', 'alice@example.com', 'Stampie is awesome!');

        $this->httpClient
            ->method('sendRequest')
            ->willReturn($response);

        $this->mailer->send($message);
    }

    /**
     * @expectedException \Stampie\Exception\ApiException
     * @expectedExceptionMessage Gateway Timeout
     */
    public function testHandleGatewayTimeout()
    {
        $response = new Response(504, [], json_encode(['message' => 'Gateway Timeout', 'code' => -1]));

        $message = $this->getMessageMock('bob@example.com', 'alice@example.com', 'Stampie is awesome!');

        $this->httpClient
            ->method('sendRequest')
            ->willReturn($response);

        $this->mailer->send($message);
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
