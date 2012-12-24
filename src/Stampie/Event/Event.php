<?php

namespace Stampie\Event;

/**
 * Adds `preventDefault` which indicates that the default behaviour should not
 * occur. Ex. when `Events::SEND` is dispatched and a listener sets `preventDefault`
 * to `true` it wont actually try and send the message.
 *
 * @package Stampie
 */
class Event extends \Symfony\Component\EventDispatcher\Event
{
    protected $preventDefault = false;

    /**
     * @param boolean $preventDefault
     */
    public function setPreventDefault($preventDefault)
    {
        $this->preventDefault = $preventDefault;
    }

    /**
     * @return boolean
     */
    public function isDefaultPrevented()
    {
        return $this->preventDefault;
    }
}
