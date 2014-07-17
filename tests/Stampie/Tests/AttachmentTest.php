<?php

namespace Stampie\Tests;

use Stampie\Attachment;

/**
 * @coversDefaultClass \Stampie\Attachment
 */
class AttachmentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     * @covers ::__construct
     */
    public function testMissingFileFails()
    {
        $file = 'filenotfound.txt';

        $attachment = $this->getAttachmentMock(array('isValidFile'));
        $attachment
            ->expects($this->once())
            ->method('isValidFile')
            ->with($file)
            ->will($this->returnValue(false))
        ;

        $attachment->__construct($file);
    }

    /**
     * @expectedException \RuntimeException
     * @covers ::__construct
     */
    public function testUnknownFileTypeFails()
    {
        $file = 'unknownfiletype.txt';

        $attachment = $this->getAttachmentMock(array('isValidFile', 'determineFileType'));
        $attachment
            ->expects($this->once())
            ->method('isValidFile')
            ->with($file)
            ->will($this->returnValue(true))
        ;

        $attachment
            ->expects($this->once())
            ->method('determineFileType')
            ->with($file)
            ->will($this->returnValue(null))
        ;

        $attachment->__construct($file);
    }

    /**
     * @covers ::__construct
     */
    public function testUnknownFileTypeSucceeds()
    {
        $file = 'unknownfiletype.txt';
        $type = 'text/plain';

        $attachment = $this->getAttachmentMock(array('isValidFile', 'determineFileType'));
        $attachment
            ->expects($this->once())
            ->method('isValidFile')
            ->with($file)
            ->will($this->returnValue(true))
        ;

        $attachment
            ->expects($this->once())
            ->method('determineFileType')
            ->with($file)
            ->will($this->returnValue($type))
        ;

        $attachment->__construct($file);

        $this->assertEquals($type, $attachment->getType(), 'The type should be determined');
    }

    /**
     * @covers ::__construct
     * @covers ::getPath
     * @covers ::getName
     * @covers ::getType
     * @covers ::getId
     */
    public function testDefaults()
    {
        $file = '/path/to/file.jpg';
        $name = 'testfile.jpg';
        $type = 'image/jpeg';
        $id = md5(time());

        $attachment = $this->getAttachmentMock(array('isValidFile'));
        $attachment
            ->expects($this->once())
            ->method('isValidFile')
            ->with($file)
            ->will($this->returnValue(true))
        ;

        $attachment->__construct($file, $name, $type, $id);

        $this->assertEquals($file, $attachment->getPath(), 'The path should be stored correctly');
        $this->assertEquals($name, $attachment->getName(), 'The name should be stored correctly');
        $this->assertEquals($type, $attachment->getType(), 'The type should be stored correctly');
        $this->assertEquals($id, $attachment->getId(), 'The ID should be stored correctly');
    }

    /**
     * @param array|null $mockMethods
     * a@return \PHPUnit_Framework_MockObject_MockObject|Attachment
     */
    protected function getAttachmentMock(array $mockMethods = null)
    {
        return $this->getMockBuilder('\\Stampie\\Attachment')
                    ->setMethods($mockMethods)
                    ->disableOriginalConstructor()
                    ->getMock();
    }
}
