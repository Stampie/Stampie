<?php

namespace Stampie\Tests\Mailer;

use Stampie\Message\TaggableInterface;
use Stampie\MessageInterface;

abstract class TaggableMessage implements MessageInterface, TaggableInterface
{
}
