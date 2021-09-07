<?php

namespace Stampie\Tests;

class MessageTest extends TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testTextWithHtmlFails()
    {
        $message = $this->getMockForAbstractClass('Stampie\Message', ['hb@peytz.dk']);
        $message->setText('<b>something</b>');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvaildEmailFails()
    {
        $this->getMockForAbstractClass('Stampie\Message', ['invalid email']);
    }

    public function testDefaults()
    {
        $message = $this->getMockForAbstractClass('Stampie\Message', ['hb@peytz.dk']);
        $message
            ->expects($this->once())
            ->method('getFrom')
            ->will($this->returnValue('henrik@bjrnskov.dk'));

        $this->assertEquals('henrik@bjrnskov.dk', $message->getReplyTo());
        $this->assertEquals('hb@peytz.dk', $message->getTo());

        $message->setHtml('<br />html');
        $message->setText('text');

        $this->assertEquals('<br />html', $message->getHtml());
        $this->assertEquals('text', $message->getText());
        $this->assertEquals([], $message->getHeaders());
        $this->assertEquals(null, $message->getCc());
        $this->assertEquals(null, $message->getBcc());
    }
}
