<?php

namespace Stampie\Tests\Mailer;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Http\Client\HttpClient;
use Stampie\Exception\ApiException;
use Stampie\Exception\HttpException;
use Stampie\Identity;
use Stampie\Mailer\SparkPost;
use Stampie\Tests\TestCase;

class SparkPostTest extends TestCase
{
    const SERVER_TOKEN = '123abc';

    /**
     * @var SparkPost
     */
    private $mailer;

    /**
     * @var HttpClient|\PHPUnit_Framework_MockObject_MockObject
     */
    private $httpClient;

    public function setUp()
    {
        $this->httpClient = $this->getMockBuilder(HttpClient::class)->getMock();
        $this->mailer = new SparkPost($this->httpClient, self::SERVER_TOKEN);
    }

    public function testSend()
    {
        $message = $this->getMessageMock(
            new Identity('bob@example.com', 'Bob'),
            [new Identity('alice@example.com', 'Alice'), 'charlie@example.com'],
            'Stampie is awesome!',
            '<h1>Stampie</h1>',
            'Stampie',
            ['X-Custom-Header' => 'My Custom Header Value']
        );

        $message
            ->expects($this->any())
            ->method('getReplyTo')
            ->will($this->returnValue('reply@example.com'));

        $message
            ->expects($this->any())
            ->method('getCc')
            ->will($this->returnValue([
                new Identity('cc-mark@example.com', 'Mark'),
                'cc-john@example.com',
            ]));

        $message
            ->expects($this->any())
            ->method('getBcc')
            ->will($this->returnValue([new Identity('bcc-sally@example.com', 'Sally')]));

        $this->httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (Request $request) {
                $body = json_decode((string) $request->getBody(), true);
                return
                    $request->getMethod() === 'POST'
                    && (string) $request->getUri() === 'https://api.sparkpost.com/api/v1/transmissions'
                    && $request->getHeaderLine('Content-Type') === 'application/json'
                    && $request->getHeaderLine('Authorization') === self::SERVER_TOKEN
                    && $body == [
                        'options' => ['transactional' => true],
                        'content' => [
                            'from' => ['email' => 'bob@example.com', 'name' => 'Bob'],
                            'headers' => [
                                'X-Custom-Header' => 'My Custom Header Value',
                                'CC' => 'Mark <cc-mark@example.com>,cc-john@example.com',
                            ],
                            'subject' => 'Stampie is awesome!',
                            'text' => 'Stampie',
                            'html' => '<h1>Stampie</h1>',
                            'reply_to' => 'reply@example.com',
                        ],
                        'recipients' => [
                            [
                                'address' => [
                                    'email' => 'alice@example.com',
                                    'header_to' => 'Alice <alice@example.com>,charlie@example.com',
                                ],
                                'tags' => [],
                            ],
                            [
                                'address' => [
                                    'email' => 'charlie@example.com',
                                    'header_to' => 'Alice <alice@example.com>,charlie@example.com',
                                ],
                                'tags' => [],
                            ],
                            [
                                'address' => [
                                    'email' => 'cc-mark@example.com',
                                    'header_to' => 'Alice <alice@example.com>,charlie@example.com',
                                ],
                                'tags' => [],
                            ],
                            [
                                'address' => [
                                    'email' => 'cc-john@example.com',
                                    'header_to' => 'Alice <alice@example.com>,charlie@example.com',
                                ],
                                'tags' => [],
                            ],
                            [
                                'address' => [
                                    'email' => 'bcc-sally@example.com',
                                    'header_to' => 'Alice <alice@example.com>,charlie@example.com',
                                ],
                                'tags' => [],
                            ],
                        ],
                    ];
            }))
            ->willReturn(new Response());

        $this->mailer->send($message);
    }

    public function testSendTaggable()
    {
        $message = $this->getTaggableMessageMock(
            'bob@example.com',
            'alice@example.com',
            'Stampie is awesome!',
            null,
            null,
            [],
            ['foo', 'bar']
        );

        $this->httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (Request $request) {
                $body = json_decode((string) $request->getBody(), true);

                return array_key_exists('recipients', $body)
                    && array_key_exists('tags', $body['recipients'][0])
                    && $body['recipients'][0]['tags'] === ['foo', 'bar'];
            }))
            ->willReturn(new Response());

        $this->mailer->send($message);
    }

    public function testSendMetadataAware()
    {
        $message = $this->getMetadataAwareMessageMock(
            'bob@example.com',
            'alice@example.com',
            'Stampie is awesome',
            null,
            null,
            [],
            ['client_name' => 'Stampie']
        );

        $this->httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (Request $request) {
                $body = json_decode((string) $request->getBody(), true);

                return array_key_exists('metadata', $body) && $body['metadata'] === ['client_name' => 'Stampie'];
            }))
            ->willReturn(new Response());

        $this->mailer->send($message);
    }

    public function testSendWithAttachments()
    {
        $message = $this->getAttachmentsMessageMock(
            $from = null,
            $to = null,
            $subject = null,
            $html = null,
            $text = null,
            $headers = [],
            array_merge(
                $attachments = [
                    $this->getAttachmentMock('paper.txt', 'paper.txt', 'text/plain', null),
                    $this->getAttachmentMock('apple.jpg', 'apple.jpg', 'image/jpeg', null),
                ],
                $images = [
                    $this->getAttachmentMock('orange.jpg', 'orange.jpg', 'image/jpeg', 'orange'),
                ]
            )
        );

        $fixtureDir = __DIR__.'/../../../Fixtures';

        $this->httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (Request $request) use ($fixtureDir) {
                $body = json_decode((string) $request->getBody(), true);

                return array_key_exists('inline_images', $body['content'])
                    && $body['content']['inline_images'] === [
                        [
                            'type' => 'image/jpeg',
                            'name' => 'orange',
                            'data' => base64_encode(file_get_contents($fixtureDir.'/orange.jpg')),
                        ]
                    ]
                    && array_key_exists('attachments', $body['content'])
                    && $body['content']['attachments'] === [
                        [
                            'type' => 'text/plain',
                            'name' => 'paper.txt',
                            'data' => base64_encode(file_get_contents($fixtureDir.'/paper.txt')),
                        ],
                        [
                            'type' => 'image/jpeg',
                            'name' => 'apple.jpg',
                            'data' => base64_encode(file_get_contents($fixtureDir.'/apple.jpg')),
                        ],
                    ];
            }))
            ->willReturn(new Response())
        ;

        $this->mailer->send($message);
    }

    /**
     * @dataProvider badRequestProvider
     */
    public function testHandleBadRequest($httpStatusCode, $expectApiException)
    {
        $this->expectException($expectApiException ? ApiException::class : HttpException::class);

        $response = new Response($httpStatusCode);

        $message = $this->getMessageMock('bob@example.com', 'alice@example.com', 'Stampie is awesome!');

        $this->httpClient
            ->method('sendRequest')
            ->willReturn($response);

        $this->mailer->send($message);
    }

    public function badRequestProvider() {
        foreach ([400, 401, 403, 404, 405, 409, 415, 422, 429] as $code) {
            yield [$code, true];
        }

        foreach ([500, 503] as $code) {
            yield [$code, false];
        }
    }
}
