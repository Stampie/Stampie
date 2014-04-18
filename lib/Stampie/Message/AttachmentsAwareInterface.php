<?php

namespace Stampie\Message;

use Stampie\Attachment;

/**
 * Represents a Message that contains Attachments
 *
 * @author Adam Averay <adam@averay.com>
 */
interface AttachmentsAwareInterface
{
    /**
     * @return Attachment[]
     */
    public function getAttachments();
}
