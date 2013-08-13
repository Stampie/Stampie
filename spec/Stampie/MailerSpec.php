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
     * @param Stampie\Identity $identity
     * @param Stampie\Message $message
     */
    function it_dispatches_event_and_calls_handler($event, $identity, $message, $dispatcher, $handler)
    {
        $dispatcher->dispatch(Events::SEND, Argument::type('Stampie\Event\MessageEvent'))->shouldBeCalled()->willReturn($event);

        $event->getTo()->willReturn($identity);
        $event->isDefaultPrevented()->willReturn(false);
        $event->getMessage()->willReturn($message);

        $handler->send($identity, $message)->shouldBeCalled()->willReturn('message-id');

        $header = $this->send($identity, $message);
        $header->getMessageId()->shouldReturn('message-id');
    }

    /**
     * @param Stampie\Event\MessageEvent $event
     * @param Stampie\Identity $identity
     * @param Stampie\Message $message
     */
    function it_skips_calling_handler_when_defaut_prevented($event, $identity, $message, $dispatcher, $handler)
    {
        $dispatcher->dispatch(Argument::any(), Argument::any())->willReturn($event);

        $event->isDefaultPrevented()->shouldBeCalled()->willReturn(true);
        $event->getTo()->willReturn($identity);

        $handler->send()->shouldNotBeCalled();

        $header = $this->send($identity, $message);
        $header->getMessageId()->shouldReturn(null);
    }

    /**
     * @param Stampie\Event\MessageEvent $event
     * @param Stampie\Identity $identity
     * @param Stampie\Message $message
     */
    function it_dispatches_failed_event_when_handler_raises_exception($event, $identity, $message, $dispatcher, $handler)
    {

        $event->getTo()->willReturn($identity);
        $event->getMessage()->willReturn($message);
        $event->isDefaultPrevented()->willReturn(false);

        $dispatcher->dispatch(Events::SEND, Argument::any())->willReturn($event);
        $dispatcher->dispatch(Events::FAILED, Argument::type('Stampie\Event\FailedMessageEvent'))->shouldBeCalled();

        $handler->send($identity, $message)->willThrow('RuntimeException');

        $this->shouldThrow('RuntimeException')->duringSend($identity, $message);
    }
}
