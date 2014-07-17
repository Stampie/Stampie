<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stampie;

/**
 * @author Christophe Coevoet <stof@notk.org>
 * @package Stampie
 */
final class StampieEvents
{
    /**
     * The SEND event occurs right before sending a message
     *
     * The event listener method receives a Stampie\Event\MessageEvent
     * instance.
     */
    const SEND = 'stampie.send';

    /**
     * The FAILED event occurs if a handler throws an exception while sending
     * the messadge.
     *
     * The event listener method receives a Stampie\Event\MessageExceptionEvent
     * instance.
     */
    const FAILED = 'stampie.failed';
}
