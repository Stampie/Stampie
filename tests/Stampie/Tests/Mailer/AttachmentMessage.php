<?php

namespace Stampie\Tests\Mailer;

use Stampie\MessageInterface;
use Stampie\Message\AttachmentsAwareInterface;

abstract class AttachmentMessage implements MessageInterface, AttachmentsAwareInterface
{
}