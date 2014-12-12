<?php

namespace Stampie\Tests\Adapter;

use Stampie\Adapter\Guzzle;
use Guzzle\Http\Message\RequestInterface;

class GuzzleTest extends \PHPUnit_Framework_TestCase
{
    private $client;

    public function setUp()
    {
        if (!interface_exists('Guzzle\Http\ClientInterface')) {
            $this->markTestSkipped('Cannot find Guzzle\Http\ClientInterface');
        }

        $this->client = $this->getMock('Guzzle\Http\ClientInterface');
    }

    public function testAccessibility()
    {
        $adapter = new Guzzle($this->client);
        $this->assertEquals($this->client, $adapter->getClient());
    }

    public function testSend()
    {
        $adapter = new Guzzle($this->client);
        $response = $this->getResponseMock();
        $request = $this->getRequestMock();
        $client = $this->client;

        $files = array(
            'filename.jpg' => 'path/to/file.jpg',
        );

        $request
            ->expects($this->once())
            ->method('addPostFiles')
            ->with($files)
            ->will($this->returnValue(null))
        ;

        $request
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue($response))
        ;

        $response
            ->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue(200))
        ;

        $response
            ->expects($this->once())
            ->method('getBody')
            ->with($this->equalTo(true))
        ;

        $client
            ->expects($this->once())
            ->method('createRequest')
            ->with(
                $this->equalTo(RequestInterface::POST),
                $this->equalTo('http://google.com'),
                $this->equalTo(array(
                    'Content-Type' => 'application/json',
                    'X-Postmark-Server-Token' => 'MySuperToken',
                )),
                $this->equalTo('content')
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
        return $this->getMock('Guzzle\Http\Message\EntityEnclosingRequestInterface', array(), array(), '', null, true);
    }

    protected function getResponseMock()
    {
        return $this->getMock('Guzzle\Http\Message\Response', array(), array(), '', null, true);
    }
}
