<?php

namespace Stampie\Tests\Adapter;

use Stampie\Mailer\Postmark;
use Stampie\Adapter\Buzz;

class BuzzTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (!class_exists('Buzz\Browser')) {
            $this->markTestSkipped('Cannot find Buzz\Browser');
        }

        $this->browser = $this->getMock('Buzz\Browser');
    }

    public function testAccesibility()
    {
        $adapter = new Buzz($this->browser);
        $this->assertEquals($this->browser, $adapter->getBrowser());
    }

    public function testSend()
    {
        $adapter = new Buzz($this->browser);
        $response = $this->getResponseMock();
        $browser = $this->browser;

        $response
            ->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue(200))
        ;

        $response
            ->expects($this->once())
            ->method('getContent')
        ;

        $browser
            ->expects($this->once())
            ->method('post')
            ->with(
                $this->equalTo('http://test.local/email'),
                $this->equalTo(array(
                    'Content-Type: application/json',
                    'X-Postmark-Server-Token: MySuperToken', 
                )),
                $this->equalTo('content')
            )
            ->will(
                $this->returnValue($response)
            )
        ;

        $adapter->send('http://test.local/email', 'content', array(
            'Content-Type' => 'application/json',
            'X-Postmark-Server-Token' => 'MySuperToken',
        ));
    }

    protected function getResponseMock()
    {
        return $this->getMock('Buzz\Message\Response');
    }
}
