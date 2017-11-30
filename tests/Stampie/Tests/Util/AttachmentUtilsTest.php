<?php

namespace Stampie\Tests\Util;

use PHPUnit\Framework\TestCase;
use Stampie\Attachment;
use Stampie\Util\AttachmentUtils;

/**
 * @coversDefaultClass \Stampie\Util\AttachmentUtils
 */
class AttachmentUtilsTest extends TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     * @covers ::processAttachments
     */
    public function testProcessInvalidAttachmentsFails()
    {
        $attachments = [
            'not an attachment object',
        ];

        AttachmentUtils::processAttachments($attachments);
    }

    /**
     * @covers ::processAttachments
     */
    public function testDefaultProcessAttachments()
    {
        $attachments = [
            $this->buildAttachment(null, 'attachment-1.txt'),
            $this->buildAttachment(null, 'attachment-2.txt'),
        ];

        $processedAttachments = AttachmentUtils::processAttachments($attachments);

        // Ensure result contains attachments
        $this->assertEquals(count($attachments), count($processedAttachments), 'All attachments should be returned');
        foreach ($attachments as $attachment) {
            $this->assertTrue(in_array($attachment, $processedAttachments, true), 'All attachments should be returned');
            $this->assertEquals($attachment, $processedAttachments[$attachment->getName()], 'Each attachment\'s key should be its name');
        }
    }

    /**
     * @covers ::processAttachments
     */
    public function testProcessAttachmentsRenaming()
    {
        $attachments = [
            $this->buildAttachment(null, 'attachment.txt'),
            $this->buildAttachment(null, 'attachment.txt'),
            $this->buildAttachment(null, 'other.txt'),
        ];
        $names = [
            'attachment.txt',
            'attachment-1.txt',
            'other.txt',
        ];

        $processedAttachments = AttachmentUtils::processAttachments($attachments);

        // Ensure result contains attachments
        $this->assertEquals(count($attachments), count($processedAttachments), 'All attachments should be returned');
        foreach ($attachments as $attachment) {
            $this->assertTrue(in_array($attachment, $processedAttachments, true), 'All attachments should be returned');
        }
        // Ensure conflicts renamed correctly
        foreach ($names as $name) {
            $this->assertTrue(isset($processedAttachments[$name]), 'Attachment keys should be set correctly');
        }
    }

    /**
     * @covers ::findUniqueName
     */
    public function testFindUniqueName()
    {
        // Make protected method accessible
        $mirror = new \ReflectionClass('\\Stampie\\Util\\AttachmentUtils');
        $method = $mirror->getMethod('findUniqueName');
        $method->setAccessible(true);

        // Non-conflicting
        $name = 'unique.txt';
        $result = $method->invoke(null, $name, [
            'other.txt',
            'name.txt',
        ]);
        $this->assertEquals($name, $result, 'Non-conflicting names should not be modified');

        // Single conflict
        $result = $method->invoke(null, 'name.txt', [
            'other.txt',
            'name.txt',
        ]);
        $this->assertEquals('name-1.txt', $result, 'Conflicting names should be modified');

        // Multiple conflicts
        $result = $method->invoke(null, 'name.txt', [
            'other.txt',
            'name.txt',
            'name-1.txt',
            'name-2.txt',
            'name-3.txt',
        ]);
        $this->assertEquals('name-4.txt', $result, 'Conflicting names should be modified');
    }

    /**
     * @param array|null $mockMethods
     * @param string     $name
     *                                a@return \PHPUnit_Framework_MockObject_MockObject|Attachment
     */
    protected function buildAttachment(array $mockMethods = null, $name = null)
    {
        $mockMethods = (array) $mockMethods;
        if (!in_array('getName', $mockMethods)) {
            $mockMethods[] = 'getName';
        }

        $mock = $this->getMockBuilder('\\Stampie\\Attachment')
                        ->setMethods($mockMethods)
                        ->disableOriginalConstructor()
                        ->getMock();

        $mock
            ->expects($this->any())
            ->method('getName')
            ->will($this->returnValue($name));

        return $mock;
    }
}
