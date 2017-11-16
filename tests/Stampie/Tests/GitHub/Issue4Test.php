<?php

namespace Stampie\Tests\GitHub;

use PHPUnit\Framework\TestCase;
use Stampie\Adapter\Response;
use Stampie\Tests\Mailer\TestPostmark;

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
        $response = new Response(422, '{}');
        $mailer = new TestPostmark($this->getMockBuilder('Http\Client\HttpClient')->getMock(), 'ServerToken');

        $mailer->handle($response);
    }
}
