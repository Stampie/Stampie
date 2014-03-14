<?php

namespace Stampie\Message;

use Stampie\AttachmentInterface;

/**
 * Represents a Message that contains Attachments
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
