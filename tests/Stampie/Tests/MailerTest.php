<?php

namespace Stampie\Tests;

class MailerTest extends \PHPUnit_Framework_TestCase
{
    public function testSettersAndGetters()
    {
        $mailer = $this->getMailerMock(array($adapter =$this->getAdapterMock(), 'Token'));

        $this->assertEquals('Token', $mailer->getServerToken());
        $this->assertEquals($adapter, $mailer->getAdapter());

        $this->setExpectedException('InvalidArgumentException', 'ServerToken cannot be empty');
        $mailer->setServerToken('');
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
