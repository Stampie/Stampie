<?php

namespace Stampie\Tests\Mailer;

use Stampie\Mailer\MailGun;

/**
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class MailGunTest extends \Stampie\Tests\BaseMailerTest
{
    const SERVER_TOKEN = 'henrik.bjrnskov.dk:myCustomKey';

    /**
     * @var MailGun
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
        $headers = $this->mailer->getHeaders();

        $this->assertTrue(isset($headers['Authorization']));
        $this->assertEquals('Basic '.base64_encode('api:myCustomKey'), $headers['Authorization']);
    }

    public function testGetFiles()
    {
        $self = $this; // PHP5.3 compatibility
        $adapter = $this->adapter;
        $token = self::SERVER_TOKEN;
        $buildMocks = function ($attachments, &$invoke) use ($self, $adapter, $token) {
            $mailer = $self->getMockBuilder('\\Stampie\\Mailer\\MailGun')
                ->setConstructorArgs([$adapter, $token])
                ->getMock()
            ;

            // Wrap protected method with accessor
            $mirror = new \ReflectionClass($mailer);
            $method = $mirror->getMethod('getFiles');
            $method->setAccessible(true);

            $invoke = function () use ($mailer, $method) {
                $args = func_get_args();
                array_unshift($args, $mailer);

                return call_user_func_array([$method, 'invoke'], $args);
            };

            $message = $self->getAttachmentsMessageMock('test@example.com', 'other@example.com', 'Subject', null, null, [], $attachments);

            return [$mailer, $message];
        };

        // Actual tests

        $attachments = [
            $this->getAttachmentMock('path-1.txt', 'path1.txt', 'text/plain', null),
            $this->getAttachmentMock('path-2.txt', 'path2.txt', 'text/plain', 'id1'),
            $this->getAttachmentMock('path-3.txt', 'path3.txt', 'text/plain', null),
            $this->getAttachmentMock('path-4.txt', 'path4.txt', 'text/plain', 'id2'),
            $this->getAttachmentMock('path-5.txt', 'path5.txt', 'text/plain', null),
        ];

        list($mailer, $message) = $buildMocks($attachments, $invoke);
        $result = $invoke($message);

        $this->assertEquals(3, count($result['attachment']), 'Attachments should be separated from inline files');
        $this->assertEquals(2, count($result['inline']), 'Attachments should be separated from inline files');

        $i = 0;
        foreach ($result['attachment'] as $key => $path) {
            $this->assertTrue(!is_numeric($key), 'Attachments should be associative');
            $this->assertEquals($attachments[$i]->getPath(), $path, 'Attachments should be formatted correctly');
            $i += 2;
        }
        $i = 1;
        foreach ($result['inline'] as $key => $path) {
            $this->assertTrue(is_numeric($key), 'Inline attachments should not be associative');
            $this->assertEquals($attachments[$i]->getPath(), $path, 'Inline attachments should be formatted correctly');
            $i += 2;
        }
    }

    protected function createMockAdapter()
    {
        return $this->getMockBuilder('Http\Client\HttpClient')->getMock();
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
