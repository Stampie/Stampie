<?php

namespace Stampie\Tests\Adapter;

use Buzz\Message\Form\FormUpload;
use Buzz\Message\RequestInterface;
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

    public function testSendWithFiles()
    {
        $files = array(
            'file1.jpg' => '/path/to/file-1.jpg',
            'file2.jpg' => '/path/to/file-2.jpg',
        );

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
            ->method('submit')
            ->with(
                $this->equalTo('http://test.local/email'),
                $this->callback(function($fields) use($files){
                    if (!isset($fields['item']) || $fields['item'] !== 'value'
                       || !isset($fields['other']) || $fields['other'] !== 'something') {
                        // Did not decode existing fields successfully
                        echo 'did not decode';
                        return false;
                    }

                    if (!isset($fields['files'])) {
                        // Did not add files
                        return false;
                    }

                    foreach ($fields['files'] as $file) {
                        if(!($file instanceof FormUpload) || !isset($files[$file->getName()]) || $files[$file->getName()] !== $file->getFile()){
                            // File invalid
                            return false;
                        }
                    }

                    return true;
                }),
                $this->equalTo(RequestInterface::METHOD_POST),
                $this->equalTo(array(
                    'Content-Type: application/json',
                    'X-Postmark-Server-Token: MySuperToken',
                ))
            )
            ->will(
                $this->returnValue($response)
            )
        ;

        $adapter->send('http://test.local/email', 'item=value&other=something', array(
            'Content-Type' => 'application/json',
            'X-Postmark-Server-Token' => 'MySuperToken',
        ), array(
            'files' => $files,
        ));
    }

    protected function getResponseMock()
    {
        return $this->getMock('Buzz\Message\Response');
    }
}
