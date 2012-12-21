<?php

namespace Stampie;

/**
 * @author Christophe Coevoet <stof@notk.org>
 * @package Stampie
 */
final class Events
{
    /**
     * The PRE_SEND event occurs before sending a message
     *
     * The event listener method receives a Stampie\Extra\Event\MessageEvent
     * instance.
     *
     * @var string
     */
    const PRE_SEND = 'stampie.pre_send';
}
