<?php

namespace Stampie\Tests\Mailer;

use Stampie\MessageInterface;
use Stampie\Message\AttachmentsContainerInterface;

abstract class AttachmentMessage implements MessageInterface, AttachmentsContainerInterface
{
}