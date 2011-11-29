<?php

namespace Stampie\Tests\Exception;

use Stampie\Exception\HttpException;

class HttpExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testImmutable()
    {
        $exception = new HttpException(400);
        $this->assertEquals(400, $exception->getStatusCode());
    }
}
