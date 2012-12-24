<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Stampie;

use Mockery;
use PHPSpec2\ObjectBehavior;
use Stampie\Events;
use Stampie\Identity;

class Mailer extends ObjectBehavior
{
    /**
     * @param Stampie\Handler\HandlerInterface $adapter
     * @param Symfony\Component\EventDispatcher\EventDispatcher $dispatcher
     * @param Stampie\Message\MessageInterface $message
     */
    function let($adapter, $dispatcher)
    {
        $this->beConstructedWith($adapter, $dispatcher);
    }

    /**
     * @param Stampie\Event\MessageEvent $event
     */
    function it_dispatches_event_when_sending($dispatcher, $message, $event)
    {
        $dispatcher->dispatch(Events::SEND, Mockery::type('Stampie\Event\MessageEvent'))->shouldBeCalled()->willReturn($event);

        $this->send(new Identity('henrik@bjrnskov.dk', 'Henrik Bjornskov'), $message);
    }

    /**
     * @param Stampie\Event\MessageEvent $event
     */
    function it_disables_sending_when_default_is_prevented($dispatcher, $handler, $event, $message)
    {
        $dispatcher->dispatch(ANY_ARGUMENTS)->willReturn($event);

        $event->isDefaultPrevented()->shouldBeCalled()->willReturn(true);

        $handler->send(ANY_ARGUMENTS)->shouldNotBeCalled();

        $this->send(new Identity('henrik@bjrnskov.dk', 'Henrik Bjornskov'), $message);
    }
}
