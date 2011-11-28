<?php

namespace Stampie\Tests;

use Stampie\Mailer;
use Stampie\MessageInterface;

class MailerTest extends \PHPUnit_Framework_TestCase
{
    const SERVER_TOKEN = "mySuperSecretServerToken";

    public function setUp()
    {
        $this->buzz = $this->getMock('Buzz\Browser');
    }

    public function testSettersAndGetters()
    {
        $mailer = new Mailer($this->buzz);
        $this->assertEquals($this->buzz, $mailer->getBrowser());
        $this->assertEquals("POSTMARK_API_TEST", $mailer->getServerToken());

        $mailer->setServerToken(static::SERVER_TOKEN, $mailer->getServerToken());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testServerTokenCannotBeEmpty()
    {
        $mailer = new Mailer($this->buzz);
        $mailer->setServerToken('');
    }
}
