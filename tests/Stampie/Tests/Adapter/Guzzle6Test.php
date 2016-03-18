<?php

namespace Stampie\Tests\Adapter;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Stampie\Adapter\Guzzle6;

class Guzzle6Test extends \PHPUnit_Framework_TestCase
{
    private $client;

    public function setUp()
    {
        if (!interface_exists('GuzzleHttp\ClientInterface')) {
            $this->markTestSkipped('Cannot find GuzzleHttp\ClientInterface');
        }

        $this->client = $this->getMock('GuzzleHttp\ClientInterface');
    }

    public function testAccessibility()
    {
        $adapter = new Guzzle6($this->client);
        $this->assertEquals($this->client, $adapter->getClient());
    }

    public function testSend()
    {
        $adapter = new Guzzle6($this->client);
        $response = $this->getResponseMock();
        $request = $this->getRequestMock();
        $client = $this->client;

        $files = array(
            'filename.jpg' => __DIR__ . '/../../../Fixtures/logo.png',
        );

        $client
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue($response))
        ;

        $response
            ->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue(200))
        ;

        $request
            ->expects($this->once())
            ->method('setBody')
        ;

        $response
            ->expects($this->once())
            ->method('getBody')
        ;

        $client
            ->expects($this->once())
            ->method('createRequest')
            ->with(
                $this->equalTo('POST'),
                $this->equalTo('http://google.com')
            )
            ->will(
                $this->returnValue($request)
            )
        ;

        $adapter->send('http://google.com', 'content', array(
            'Content-Type' => 'application/json',
            'X-Postmark-Server-Token' => 'MySuperToken',
        ), $files);
    }

    protected function getRequestMock()
    {
        return $this->getMock(RequestInterface::class, array(), array(), '', null, true);
    }

    protected function getResponseMock()
    {
        return $this->getMock(ResponseInterface::class, array(), array(), '', null, true);
    }
}
