<?php

namespace Stampie\Tests\Adapter;

use Stampie\Adapter\Guzzle;
use Guzzle\Http\Message\RequestInterface;

class GuzzleTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->client = $this->getMock('Guzzle\Service\Client');
    }

    public function testAccesibility()
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
        ));
    }

    protected function getRequestMock()
    {
        return $this->getMock('Guzzle\Http\Message\Request', array(), array(), '', null, true);
    }

    protected function getResponseMock()
    {
        return $this->getMock('Guzzle\Http\Message\Response', array(), array(), '', null, true);
    }
}
