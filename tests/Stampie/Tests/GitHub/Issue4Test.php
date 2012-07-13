<?php

namespace Stampie\Tests\GitHub;

use Stampie\Adapter\Response;
use Stampie\Tests\Mailer\TestPostmark;

require_once __DIR__.'/../Mailer/PostmarkTest.php';

/**
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class Issue4Test extends \PHPUnit_Framework_TestCase
{
    public function testMissingErrorMessageInResponse()
    {
        $response = new Response(422, '{}');
        $mailer = new TestPostmark($this->getMock('Stampie\Adapter\AdapterInterface'), 'ServerToken');

        $this->setExpectedException('Stampie\Exception\ApiException', 'Unprocessable Entity');

        $mailer->handle($response);
    }
}
