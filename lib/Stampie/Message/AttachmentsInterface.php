<?php

namespace Stampie\Message;

use Stampie\AttachmentInterface;

/**
 * Represents an Attachment. An Attachment is a container for a file
 * that will be included with a Message.
 *
 * @author Adam Averay <adam@averay.com>
 */
interface AttachmentsInterface
{
    /**
     * @return AttachmentInterface[]
     */
    public function getAttachments();
}
