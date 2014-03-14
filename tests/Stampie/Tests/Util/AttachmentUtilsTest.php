<?php

namespace Stampie\Tests\Util;

use Stampie\Util\AttachmentUtils;
use Stampie\AttachmentInterface;

/**
 * @coversDefaultClass \Stampie\Util\AttachmentUtils
 */
class AttachmentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     * @covers ::processAttachments
     */
    public function testProcessInvalidAttachmentsFails()
    {
        $attachments = array(
            'not an attachment object',
        );

        AttachmentUtils::processAttachments($attachments, $this->buildIllegalCallback());
    }

    /**
     * @covers ::processAttachments
     */
    public function testDefaultProcessAttachments()
    {
        $attachments = array(
            $this->buildAttachment(null, 'attachment-1.txt'),
            $this->buildAttachment(null, 'attachment-2.txt'),
        );

        $calledAttachments = array();
        $callback = function($name, $attachment) use(&$calledAttachments){
            $calledAttachments[] = $attachment->getName();

            return $name;
        };

        $result = AttachmentUtils::processAttachments($attachments, $callback);

        // Ensure called for every attachment
        $this->assertEquals(count($attachments), count($calledAttachments), 'Callback should be called for every attachment');
        foreach ($attachments as $attachment) {
            $this->assertTrue(in_array($attachment->getName(), $calledAttachments), 'Callback should be called for every attachment');
        }

        // Ensure result contains attachments
        $this->assertEquals(count($attachments), count($result), 'All processed attachments should be returned');
        foreach ($attachments as $attachment) {
            $this->assertTrue(in_array($attachment->getName(), $calledAttachments), 'All processed attachments should be returned');
        }
    }

    /**
     * @covers ::processAttachments
     */
    public function testProcessAttachmentsSkipping()
    {
        $skipped = 1;

        $attachments = array(
            $this->buildAttachment(null, 'attachment-1.txt'),
            $this->buildAttachment(null, 'attachment-2.txt'),
            $this->buildAttachment(null, 'attachment-3.txt'),
        );

        $count = 0;
        $calledAttachments = array();
        $callback = function($name, $attachment) use(&$calledAttachments, &$count, $skipped){
            $calledAttachments[] = $attachment->getName();

            if ($count === $skipped) {
                $count++;
                return null; // Should not be included
            }

            $count++;
            return $name;
        };

        $result = AttachmentUtils::processAttachments($attachments, $callback);

        $skipped = $attachments[$skipped];

        // Ensure called for every attachment
        $this->assertEquals(count($attachments), count($calledAttachments), 'Callback should be called for every attachment');
        foreach ($attachments as $attachment) {
            $this->assertTrue(in_array($attachment->getName(), $calledAttachments), 'Callback should be called for every attachment');
        }

        // Ensure skipped attachment not returned
        $this->assertEquals(count($attachments)-1, count($result), 'Only attachments returning a callback value should be returned');
        $this->assertFalse(in_array($skipped->getName(), $result), 'Skipped attachments should not be returned');
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
        $name   = 'unique.txt';
        $result = $method->invoke(null, $name, array(
            'other.txt',
            'name.txt',
        ));
        $this->assertEquals($name, $result, 'Non-conflicting names should not be modified');

        // Single conflict
        $result = $method->invoke(null, 'name.txt', array(
            'other.txt',
            'name.txt',
        ));
        $this->assertEquals('name-1.txt', $result, 'Conflicting names should be modified');

        // Multiple conflicts
        $result = $method->invoke(null, 'name.txt', array(
            'other.txt',
            'name.txt',
            'name-1.txt',
            'name-2.txt',
            'name-3.txt',
        ));
        $this->assertEquals('name-4.txt', $result, 'Conflicting names should be modified');
    }


    /**
     * @return callable
     */
    protected function buildIllegalCallback()
    {
        $self = $this; // PHP5.3 compatibility

        return function() use($self){
            $self->fail('Callback should not be called');
        };
    }

    /**
     * @param array|null $mockMethods
     * @param string $name
     * a@return \PHPUnit_Framework_MockObject_MockObject|AttachmentInterface
     */
    protected function buildAttachment(array $mockMethods = null, $name = null)
    {
        $mockMethods = (array)$mockMethods;
        if (!in_array('getName', $mockMethods)) {
            $mockMethods[] = 'getName';
        }

        $mock    = $this->getMockBuilder('\\Stampie\\AttachmentInterface')
                        ->setMethods($mockMethods)
                        ->getMockForAbstractClass();

        $mock
            ->expects($this->any())
            ->method('getName')
            ->will($this->returnValue($name))
        ;

        return $mock;
    }
}
