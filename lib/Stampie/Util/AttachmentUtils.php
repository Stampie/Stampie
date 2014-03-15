<?php

namespace Stampie\Util;

use Stampie\AttachmentInterface;

/**
 * Stampie Attachment utility functions
 *
 * @author Adam Averay <adam@averay.com>
 */
abstract class AttachmentUtils
{
    /**
     * Applies a function to each attachment, and finds a unique name for any conflicting names
     *
     * @param AttachmentInterface[] $attachments
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    public static function processAttachments(array $attachments)
    {
        $processed    = array();

        foreach ($attachments as $attachment) {
            if (!($attachment instanceof AttachmentInterface)) {
                throw new \InvalidArgumentException('Attachments must implement Stampie\\AttachmentInterface');
            }

            $name = $attachment->getName();

            if (isset($processed[$name])) {
                // Name conflict
                $name = static::findUniqueName($name, array_keys($processed));
            }

            $processed[$name]    = $attachment;
        }

        return $processed;
    }

    /**
     * @param string $name    The name to make unique
     * @param array $claimed  Names already in use to avoid
     * @return string         A unique name
     */
    public static function findUniqueName($name, array $claimed)
    {
        $ext      = pathinfo($name, \PATHINFO_EXTENSION);
        $basename = substr($name, 0, -strlen('.'.$ext));

        $i = 0;
        while (in_array($name, $claimed)) {
            $i++;
            $name    = $basename.'-'.$i.'.'.$ext;
        }

        return $name;
    }
}
