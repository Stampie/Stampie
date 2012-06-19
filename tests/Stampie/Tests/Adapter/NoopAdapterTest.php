<?php

namespace Stampie\Tests\Adapter;

use Stampie\Adapter\NoopAdapter;

/**
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class NoopAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->adapter = new NoopAdapter();
    }

    public function testSend()
    {
        $response = $this->adapter->send('http://endpoint', 'content');

        $this->assertInstanceOf('Stampie\Adapter\ResponseInterface', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('Message was sent [NoopAdapter]', $response->getContent());

    }
}
