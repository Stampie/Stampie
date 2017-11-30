<?php

namespace Stampie\Tests\Adapter;

use PHPUnit\Framework\TestCase;
use Stampie\Adapter\Response;

class ResponseTest extends TestCase
{
    public function testImmutable()
    {
        $response = new Response(200, 'Content');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Content', $response->getContent());
        $this->assertEquals('OK', $response->getStatusText());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testUnknownStatusCode()
    {
        $response = new Response(0, 'Content');
    }

    /**
     * @dataProvider isSuccessfullDataProvider
     */
    public function testIsSuccessfull($statusCode)
    {
        $response = new Response($statusCode, '');
        $this->assertTrue($response->isSuccessful());
    }

    public function isSuccessfullDataProvider()
    {
        return [
            range(200, 206),
        ];
    }
}
