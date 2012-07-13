<?php

namespace Stampie\Tests;

class MailerTest extends \PHPUnit_Framework_TestCase
{
    protected $adapter;

    /**
     * @var \Stampie\MailerInterface
     */
    protected $mailer;

    public function setUp()
    {
        $this->mailer = $this->getMailerMock(array(
            $this->adapter = $this->getAdapterMock(),
            'Token',
        ));
    }

    public function testSettersAndGetters()
    {

        $this->assertEquals('Token', $this->mailer->getServerToken());
        $this->assertEquals($this->adapter, $this->mailer->getAdapter());

    }

    public function testServerTokenCannotBeEmpty()
    {
        $this->setExpectedException('InvalidArgumentException', 'ServerToken cannot be empty');
        $this->mailer->setServerToken('');
    }

    public function testSendSuccessful()
    {
        $mailer = $this->mailer;
        $adapter = $this->mailer->getAdapter();

        $mailer
            ->expects($this->once())
            ->method('format')
        ;

        $mailer
            ->expects($this->never())
            ->method('handle')
        ;

        $adapter
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue(
                $this->getResponseMock(true)
            ))
        ;

        $this->assertTrue($this->mailer->send($this->getMock('Stampie\MessageInterface')));
    }

    public function testUnsuccessfulSendCallsHandle()
    {
        $this
            ->adapter
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue($this->getResponseMock(false)))
        ;

        $this
            ->mailer
            ->expects($this->once())
            ->method('handle')
        ;

        $this->mailer->send($this->getMock('Stampie\MessageInterface'));
    }

    protected function getResponseMock($isSuccessfull)
    {
        $response = $this->getMock('Stampie\Adapter\ResponseInterface');
        $response
            ->expects($this->any())
            ->method('isSuccessful')
            ->will($this->returnValue($isSuccessfull))
        ;

        return $response;
    }

    protected function getAdapterMock()
    {
        return $this->getMock('Stampie\Adapter\AdapterInterface');
    }

    protected function getMailerMock(array $args = array())
    {
        return $this->getMockForAbstractClass('Stampie\Mailer', $args);
    }
}
