<?php

namespace Stampie\Tests\Mailer;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Http\Client\HttpClient;
use Stampie\Exception\ApiException;
use Stampie\Exception\HttpException;
use Stampie\Identity;
use Stampie\Mailer\Mailjet;
use Stampie\Tests\TestCase;

class MailjetTest extends TestCase
{
    const SERVER_TOKEN = 'myPublicAPIKey:myPrivateAPIKey';

    /**
     * @var Mailjet
     */
    private $mailer;

    /**
     * @var HttpClient|\PHPUnit_Framework_MockObject_MockObject
     */
    private $httpClient;

    public function setUp()
    {
        parent::setUp();

        $this->httpClient = $this->getMockBuilder(HttpClient::class)->getMock();
        $this->mailer = new Mailjet($this->httpClient, self::SERVER_TOKEN);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Mailjet uses a "publicApiKey:privateApiKey" based ServerToken
     */
    public function testServerTokenMissingDelimiter()
    {
        new Mailjet($this->httpClient, 'missingDelimiter');
    }

    public function testServerToken()
    {
        $this->assertEquals(self::SERVER_TOKEN, $this->mailer->getServerToken());
    }

    public function testSend()
    {
        $message = $this->getMessageMock('bob@example.com', 'alice@example.com', 'Stampie is awesome!', 'Trying out Stampie!', null, [
            'X-Custom-Header' => 'My Custom Header Value',
        ]);

        $this->httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (Request $request) {
                $body = json_decode((string) $request->getBody(), true);

                $this->assertEquals('POST', $request->getMethod());
                $this->assertEquals('https://api.mailjet.com/v3.1/send', (string) $request->getUri());
                $this->assertEquals('application/json', $request->getHeaderLine('Accept'));
                $this->assertEquals('application/json', $request->getHeaderLine('Content-Type'));
                $this->assertEquals(sprintf('Basic %s', base64_encode(self::SERVER_TOKEN)), $request->getHeaderLine('Authorization'));
                $this->assertEquals([
                    'Messages' => [
                        [
                            'From' => [
                                'Email' => 'bob@example.com',
                            ],
                            'To' => [
                                [
                                    'Email' => 'alice@example.com',
                                ],
                            ],
                            'Subject' => 'Stampie is awesome!',
                            'HTMLPart' => 'Trying out Stampie!',
                            'Headers' => [
                                'X-Custom-Header' => 'My Custom Header Value',
                            ],
                        ],
                    ],
                ], $body);

                return true;
            }))
            ->willReturn(new Response());

        $this->mailer->send($message);
    }

    public function testSendTaggable()
    {
        $message = $this->getTaggableMessageMock('bob@example.com', 'alice@example.com', 'Stampie is awesome!', 'Trying out Stampie!', null, [], 'tag');

        $this->httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (Request $request) {
                $body = json_decode((string) $request->getBody(), true);

                $this->assertEquals([
                    'Messages' => [
                        [
                            'From' => [
                                'Email' => 'bob@example.com',
                            ],
                            'To' => [
                                [
                                    'Email' => 'alice@example.com',
                                ],
                            ],
                            'Subject' => 'Stampie is awesome!',
                            'HTMLPart' => 'Trying out Stampie!',
                            'MonitoringCategory' => 'tag',
                        ],
                    ],
                ], $body);

                return true;
            }))
            ->willReturn(new Response());

        $this->mailer->send($message);
    }

    public function testSendMetadataAware()
    {
        $message = $this->getMetadataAwareMessageMock('bob@example.com', 'alice@example.com', 'Stampie is awesome!', 'Trying out Stampie!', null, [], [
            'client_name' => 'Stampie',
        ]);

        $this->httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (Request $request) {
                $body = json_decode((string) $request->getBody(), true);

                $this->assertEquals([
                    'Messages' => [
                        [
                            'From' => [
                                'Email' => 'bob@example.com',
                            ],
                            'To' => [
                                [
                                    'Email' => 'alice@example.com',
                                ],
                            ],
                            'Subject' => 'Stampie is awesome!',
                            'HTMLPart' => 'Trying out Stampie!',
                            'EventPayload' => [
                                'client_name' => 'Stampie',
                            ],
                        ],
                    ],
                ], $body);

                return true;
            }))
            ->willReturn(new Response());

        $this->mailer->send($message);
    }

    public function testSendWithAttachments()
    {
        $message = $this->getAttachmentsMessageMock('bob@example.com', 'alice@example.com', 'Stampie is awesome!', null, null, [], [
            $this->getAttachmentMock('path-1.txt', 'path1.txt', 'text/plain', null),
            $this->getAttachmentMock('path-2.txt', 'path2.txt', 'text/plain', null),
            $this->getAttachmentMock('logo.png', 'logo.png', 'image/png', 'contentid1'),
        ]);

        $this->httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (Request $request) {
                $body = json_decode((string) $request->getBody(), true);

                $this->assertEquals('application/json', $request->getHeaderLine('Content-Type'));
                $this->assertEquals([
                    'Messages' => [
                        [
                            'From' => [
                                'Email' => 'bob@example.com',
                            ],
                            'To' => [
                                [
                                    'Email' => 'alice@example.com',
                                ],
                            ],
                            'Subject' => 'Stampie is awesome!',
                            'Attachments' => [
                                [
                                    'ContentType' => 'text/plain',
                                    'Filename' => 'path1.txt',
                                    'Base64Content' => base64_encode(file_get_contents(__DIR__.'/../../../Fixtures/path-1.txt')),
                                ],
                                [
                                    'ContentType' => 'text/plain',
                                    'Filename' => 'path2.txt',
                                    'Base64Content' => base64_encode(file_get_contents(__DIR__.'/../../../Fixtures/path-2.txt')),
                                ],
                            ],
                            'InlinedAttachments' => [
                                [
                                    'ContentType' => 'image/png',
                                    'Filename' => 'logo.png',
                                    'Base64Content' => base64_encode(file_get_contents(__DIR__.'/../../../Fixtures/logo.png')),
                                    'ContentID' => 'contentid1',
                                ],
                            ],
                        ],
                    ],
                ], $body);

                return true;
            }))
            ->willReturn(new Response());

        $this->mailer->send($message);
    }

    /**
     * @dataProvider senderProvider
     */
    public function testFormatSender($sender, $expectedFormat)
    {
        $message = $this->getMessageMock($sender, 'alice@example.com', 'Stampie is awesome!');

        $this->httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (Request $request) use ($expectedFormat) {
                $body = json_decode((string) $request->getBody(), true);

                $this->assertEquals([
                    'Messages' => [
                        [
                            'From' => $expectedFormat,
                            'To' => [
                                [
                                    'Email' => 'alice@example.com',
                                ],
                            ],
                            'Subject' => 'Stampie is awesome!',
                        ],
                    ],
                ], $body);

                return true;
            }))
            ->willReturn(new Response());

        $this->mailer->send($message);
    }

    /**
     * @dataProvider recipientsProvider
     */
    public function testFormatRecipients($recipients, $expectedFormat)
    {
        $message = $this->getMessageMock('bob@example.com', $recipients, 'Stampie is awesome!');

        $this->httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (Request $request) use ($expectedFormat) {
                $body = json_decode((string) $request->getBody(), true);

                $this->assertEquals([
                    'Messages' => [
                        [
                            'From' => [
                                'Email' => 'bob@example.com',
                            ],
                            'To' => $expectedFormat,
                            'Subject' => 'Stampie is awesome!',
                        ],
                    ],
                ], $body);

                return true;
            }))
            ->willReturn(new Response());

        $this->mailer->send($message);
    }

    /**
     * @dataProvider errorProvider
     */
    public function testHandleError($statusCode, $content, $exceptionType, $exceptionMessage)
    {
        $response = new Response($statusCode, [], $content);

        if (method_exists($this, 'expectException')) {
            $this->expectException($exceptionType);
            $this->expectExceptionMessage($exceptionMessage);
        } else {
            $this->setExpectedException($exceptionType, $exceptionMessage);
        }

        $message = $this->getMessageMock('bob@example.com', 'alice@example.com', 'Stampie is awesome!');

        $this->httpClient
            ->method('sendRequest')
            ->willReturn($response);

        $this->mailer->send($message);
    }

    public function senderProvider()
    {
        return [
            [
                'alice@example.com',
                ['Email' => 'alice@example.com'],
            ],
            [
                new Identity('alice@example.com'),
                ['Email' => 'alice@example.com'],
            ],
            [
                new Identity('alice@example.com', 'Alice Example'),
                ['Email' => 'alice@example.com', 'Name' => 'Alice Example'],
            ],
        ];
    }

    public function recipientsProvider()
    {
        return [
            [
                'alice@example.com',
                [
                    ['Email' => 'alice@example.com'],
                ],
            ],
            [
                [new Identity('alice@example.com')],
                [
                    ['Email' => 'alice@example.com'],
                ],
            ],
            [
                [new Identity('alice@example.com', 'Alice Example')],
                [
                    ['Email' => 'alice@example.com', 'Name' => 'Alice Example'],
                ],
            ],
            [
                ['toto@example.com', new Identity('alice@example.com'), new Identity('henrik@bjrnskov.dk', 'Henrik Bjrnskov')],
                [
                    ['Email' => 'toto@example.com'],
                    ['Email' => 'alice@example.com'],
                    ['Email' => 'henrik@bjrnskov.dk', 'Name' => 'Henrik Bjrnskov'],
                ],
            ],
        ];
    }

    public function errorProvider()
    {
        return [
            [500, '', HttpException::class, 'Internal Server Error'],
            [400, <<<'ERROR'
{
  "ErrorIdentifier": "06df1144-c6f3-4ca7-8885-7ec5d4344113",
  "ErrorCode": "mj-0002",
  "ErrorMessage": "Malformed JSON, please review the syntax and properties types.",
  "StatusCode": 400
}
ERROR
                , ApiException::class, 'Malformed JSON, please review the syntax and properties types.'],
            [400, <<<ERROR
{
  "Messages": [
    {
      "Status": "error",
      "Errors": [
        {
          "ErrorIdentifier": "f987008f-251a-4dff-8ffc-40f1583ad7bc",
          "ErrorCode": "mj-0004",
          "StatusCode": 400,
          "ErrorMessage": "Type mismatch. Expected type \"array of emails\".",
          "ErrorRelatedTo": ["HTMLPart", "TemplateID"]
        },
        {
          "ErrorIdentifier": "8e28ac9c-1fd7-41ad-825f-1d60bc459189",
          "ErrorCode": "mj-0005",
          "StatusCode": 400,
          "ErrorMessage": "The To is mandatory but missing from the input",
          "ErrorRelatedTo": ["To"]
        }
      ]
    }
  ]
}
ERROR
                , ApiException::class, 'Type mismatch. Expected type "array of emails"., The To is mandatory but missing from the input'],
        ];
    }
}
