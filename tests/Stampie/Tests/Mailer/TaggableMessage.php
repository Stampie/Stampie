<?php

namespace Stampie\Tests\Mailer;

use Stampie\MessageInterface;
use Stampie\Message\TaggableInterface;

abstract class TaggableMessage implements MessageInterface, TaggableInterface
{
}
