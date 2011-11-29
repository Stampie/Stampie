<?php

namespace Stampie\Tests\Adapter;

use Stampie\Adapter\Response;

class ResponseTest extends \PHPUnit_Framework_TestCase
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
}
