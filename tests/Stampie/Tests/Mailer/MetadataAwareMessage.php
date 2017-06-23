<?php

namespace Stampie\Tests\Mailer;

use Stampie\Message\MetadataAwareInterface;
use Stampie\MessageInterface;

abstract class MetadataAwareMessage implements MessageInterface, MetadataAwareInterface
{
}
