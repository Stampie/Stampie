<?php

namespace Stampie\Tests\Mailer;

use Stampie\Mailer\MailGun;

/**
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class MailGunTest extends \PHPUnit_Framework_TestCase
{
    const SERVER_TOKEN = 'henrik.bjrnskov.dk:myCustomKey';

    private $adapter;

    /**
     * @var TestMailGun
     */
    private $mailer;

    public function setUp()
    {
        $this->adapter = $this->createMockAdapter();
        $this->mailer = new TestMailGun($this->adapter, self::SERVER_TOKEN);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testServerTokenMissingDelimeter()
    {
        $mailer = new MailGun($this->adapter, 'missingDelimeter');
    }

    public function testServerToken()
    {
        $this->assertEquals(self::SERVER_TOKEN, $this->mailer->getServerToken());
    }

    public function testEndpoint()
    {
        $this->assertEquals('https://api.mailgun.net/v2/henrik.bjrnskov.dk/messages', $this->mailer->getEndpoint());
    }

    public function testHeaders()
    {
        $this->assertEquals(array(
            'Authorization' => 'Basic ' . base64_encode('api:myCustomKey'),
        ), $this->mailer->getHeaders());
    }

    protected function createMockAdapter()
    {
        return $this->getMock('Stampie\Adapter\AdapterInterface');
    }
}

class TestMailGun extends MailGun
{
    public function getEndpoint()
    {
        return parent::getEndpoint();
    }

    public function getHeaders()
    {
        return parent::getHeaders();
    }
}
