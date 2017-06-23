<?php

namespace Stampie\Tests\Mailer;

use Stampie\Message\AttachmentsAwareInterface;
use Stampie\MessageInterface;

abstract class AttachmentMessage implements MessageInterface, AttachmentsAwareInterface
{
}
