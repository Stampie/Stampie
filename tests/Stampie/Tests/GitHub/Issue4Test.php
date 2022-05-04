<?php

namespace Stampie\Tests\GitHub;

use GuzzleHttp\Psr7\Response;
use Http\Client\HttpClient;
use Stampie\Exception\ApiException;
use Stampie\Mailer\Postmark;
use Stampie\Tests\TestCase;

/**
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class Issue4Test extends TestCase
{
    public function testMissingErrorMessageInResponse()
    {
        $httpClient = $this->getMockBuilder(HttpClient::class)->getMock();
        $httpClient->method('sendRequest')->willReturn(new Response(422, [], '{}'));

        $mailer = new Postmark($httpClient, 'ServerToken');

        $message = $this->getMessageMock('bob@example.com', 'alice@example.com', 'Stampie is awesome');

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Unprocessable Entity');

        $mailer->send($message);
    }
}
