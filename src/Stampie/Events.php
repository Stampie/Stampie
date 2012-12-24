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
final class Events
{
    /**
     * The SEND event occurs right before sending a message
     *
     * The event listener method receives a Stampie\Event\MessageEvent
     * instance.
     *
     * @var string
     */
    const SEND = 'stampie.send';
}
