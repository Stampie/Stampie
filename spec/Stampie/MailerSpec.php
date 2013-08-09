<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Stampie;

use Prophecy\Argument;
use Stampie\Events;

class MailerSpec extends \PhpSpec\ObjectBehavior
{
    /**
     * @param Stampie\Handler $handler
     * @param Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     */
    function let($handler, $dispatcher)
    {
        $this->beConstructedWith($handler, $dispatcher);
    }

    /**
     * @param Stampie\Event\MessageEvent $event
     * @param Stampie\Message\Identity $identity
     * @param Stampie\Message $message
     */
    function it_dispatches_event_when_sending($event, $identity, $message, $dispatcher)
    {
        $dispatcher->dispatch(Events::SEND, Argument::type('Stampie\Event\MessageEvent'))->shouldBeCalled()->willReturn($event);

        $event->getTo()->willReturn($identity);
        $event->isDefaultPrevented()->willReturn(false);
        $event->getMessage()->willReturn($message);

        $this->send($identity, $message);
    }

    /**
     * @param Stampie\Event\MessageEvent $event
     * @param Stampie\Message\Identity $identity
     * @param Stampie\Message $message
     */
    function it_disables_sending_when_default_is_prevented($event, $identity, $message, $dispatcher, $handler)
    {
        $dispatcher->dispatch(Argument::any(), Argument::any())->willReturn($event);

        $event->isDefaultPrevented()->shouldBeCalled()->willReturn(true);
        $event->getTo()->willReturn($identity);

        $handler->send(Argument::any())->shouldNotBeCalled();

        $this->send($identity, $message);
    }
}
