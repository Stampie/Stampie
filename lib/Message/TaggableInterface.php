<?php

namespace Stampie\Message;

/**
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
interface TaggableInterface
{
    /**
     * The tag(s) attached to the message.
     *
     * Tags should typically be used to distinguish the different categories of messages
     * sent by your application (invitation, password recovery, notification...).
     *
     * Most providers supporting this feature only allow to use a limited number of tags
     * in the application.
     *
     * @return string|array
     */
    public function getTag();
}
