<?php

namespace Stampie\Tests;

class MailerTest extends \PHPUnit_Framework_TestCase
{
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

    protected function getAdapterMock()
    {
        return $this->getMock('Stampie\Adapter\AdapterInterface');
    }

    protected function getMailerMock(array $args = array())
    {
        return $this->getMockForAbstractClass('Stampie\Mailer', $args);
    }
}
