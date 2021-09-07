<?php

namespace Stampie\Tests;

/**
 * @coversDefaultClass \Stampie\Attachment
 */
class AttachmentTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testMissingFileFails()
    {
        $file = 'filenotfound.txt';

        $attachment = $this->getMockBuilder('\\Stampie\\Attachment')
            ->onlyMethods(['isValidFile'])
            ->disableOriginalConstructor()
            ->getMock();
        $attachment
            ->expects($this->once())
            ->method('isValidFile')
            ->with($file)
            ->will($this->returnValue(false));

        $this->expectException(\InvalidArgumentException::class);

        $attachment->__construct($file);
    }

    /**
     * @covers ::__construct
     */
    public function testUnknownFileTypeFails()
    {
        $file = 'unknownfiletype.txt';

        $attachment = $this->getMockBuilder('\\Stampie\\Attachment')
            ->setMethods(['isValidFile', 'determineFileType'])
            ->disableOriginalConstructor()
            ->getMock();
        $attachment
            ->expects($this->once())
            ->method('isValidFile')
            ->with($file)
            ->will($this->returnValue(true));

        $attachment
            ->expects($this->once())
            ->method('determineFileType')
            ->with($file)
            ->will($this->returnValue(null));

        $this->expectException(\RuntimeException::class);

        $attachment->__construct($file);
    }

    /**
     * @covers ::__construct
     */
    public function testUnknownFileTypeSucceeds()
    {
        $file = 'unknownfiletype.txt';
        $type = 'text/plain';

        $attachment = $this->getMockBuilder('\\Stampie\\Attachment')
            ->onlyMethods(['isValidFile', 'determineFileType'])
            ->disableOriginalConstructor()
            ->getMock();
        $attachment
            ->expects($this->once())
            ->method('isValidFile')
            ->with($file)
            ->will($this->returnValue(true));

        $attachment
            ->expects($this->once())
            ->method('determineFileType')
            ->with($file)
            ->will($this->returnValue($type));

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

        $attachment = $this->getMockBuilder('\\Stampie\\Attachment')
            ->onlyMethods(['isValidFile'])
            ->disableOriginalConstructor()
            ->getMock();
        $attachment
            ->expects($this->once())
            ->method('isValidFile')
            ->with($file)
            ->will($this->returnValue(true));

        $attachment->__construct($file, $name, $type, $id);

        $this->assertEquals($file, $attachment->getPath(), 'The path should be stored correctly');
        $this->assertEquals($name, $attachment->getName(), 'The name should be stored correctly');
        $this->assertEquals($type, $attachment->getType(), 'The type should be stored correctly');
        $this->assertEquals($id, $attachment->getId(), 'The ID should be stored correctly');
    }
}
