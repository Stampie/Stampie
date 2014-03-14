<?php

namespace Stampie\Tests\Mailer;

use Stampie\MessageInterface;
use Stampie\Message\AttachmentsInterface;

abstract class AttachmentMessage implements MessageInterface, AttachmentsInterface
{
}