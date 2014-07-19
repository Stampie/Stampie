<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Stampie;

use Prophecy\Argument;
use Stampie\StampieEvents;

class MailerSpec extends \PhpSpec\ObjectBehavior
{
    /**
     * @param Stampie\Provider $provider
     * @param Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     */
    function let($provider, $dispatcher)
    {
        $this->beConstructedWith($provider, $dispatcher);
    }

    /**
     * @param Stampie\Event\MessageEvent $event
     * @param Stampie\Identity $identity
     * @param Stampie\Message $message
     */
    function it_dispatches_event_and_calls_handler($event, $identity, $message, $dispatcher, $provider)
    {
        $dispatcher->dispatch(StampieEvents::SEND, Argument::type('Stampie\Event\MessageEvent'))->shouldBeCalled()->willReturn($event);

        $event->getTo()->willReturn($identity);
        $event->isDefaultPrevented()->willReturn(false);
        $event->getMessage()->willReturn($message);

        $provider->send($identity, $message)->shouldBeCalled()->willReturn('message-id');

        $header = $this->send($identity, $message);
        $header->getMessageId()->shouldReturn('message-id');
    }

    /**
     * @param Stampie\Event\MessageEvent $event
     * @param Stampie\Identity $identity
     * @param Stampie\Message $message
     */
    function it_skips_calling_provider_when_defaut_prevented($event, $identity, $message, $dispatcher, $provider)
    {
        $dispatcher->dispatch(Argument::any(), Argument::any())->willReturn($event);

        $event->isDefaultPrevented()->shouldBeCalled()->willReturn(true);
        $event->getTo()->willReturn($identity);

        $provider->send()->shouldNotBeCalled();

        $header = $this->send($identity, $message);
        $header->getMessageId()->shouldReturn(null);
    }

    /**
     * @param Stampie\Event\MessageEvent $event
     * @param Stampie\Identity $identity
     * @param Stampie\Message $message
     */
    function it_dispatches_failed_event_when_provider_raises_exception($event, $identity, $message, $dispatcher, $provider)
    {

        $event->getTo()->willReturn($identity);
        $event->getMessage()->willReturn($message);
        $event->isDefaultPrevented()->willReturn(false);

        $dispatcher->dispatch(StampieEvents::SEND, Argument::any())->willReturn($event);
        $dispatcher->dispatch(StampieEvents::FAILED, Argument::type('Stampie\Event\FailedMessageEvent'))->shouldBeCalled();

        $provider->send($identity, $message)->willThrow('RuntimeException');

        $this->shouldThrow('RuntimeException')->duringSend($identity, $message);
    }
}
