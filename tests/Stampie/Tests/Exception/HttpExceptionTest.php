<?php

namespace Stampie\Tests\Exception;

use PHPUnit\Framework\TestCase;
use Stampie\Exception\HttpException;

class HttpExceptionTest extends TestCase
{
    public function testImmutable()
    {
        $exception = new HttpException(400);
        $this->assertEquals(400, $exception->getStatusCode());
    }
}
