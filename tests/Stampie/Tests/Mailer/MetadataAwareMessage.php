<?php

namespace Stampie\Tests\Mailer;

use Stampie\MessageInterface;
use Stampie\Message\MetadataAwareInterface;

abstract class MetadataAwareMessage implements MessageInterface, MetadataAwareInterface
{
}
