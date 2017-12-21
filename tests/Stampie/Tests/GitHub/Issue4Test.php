<?php

namespace Stampie\Tests\GitHub;

use GuzzleHttp\Psr7\Response;
use Stampie\Mailer\Postmark;
use Stampie\Tests\TestCase;

/**
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class Issue4Test extends TestCase
{
    /**
     * @expectedException \Stampie\Exception\ApiException
     * @expectedExceptionMessage Unprocessable Entity
     */
    public function testMissingErrorMessageInResponse()
    {
        $httpClient = $this->getMockBuilder('Http\Client\HttpClient')->getMock();
        $httpClient->method('sendRequest')->willReturn(new Response(422, [], '{}'));

        $mailer = new Postmark($httpClient, 'ServerToken');

        $message = $this->getMessageMock('bob@example.com', 'alice@example.com', 'Stampie is awesome');

        $mailer->send($message);
    }
}
