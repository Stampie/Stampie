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
use Stampie\Message\MessageHeader;

class MailerSpec extends \PhpSpec\ObjectBehavior
{
    /**
     * @param Stampie\Carrier $carrier
     * @param Stampie\Adapter $adapter
     * @param Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     */
    function let($carrier, $adapter, $dispatcher)
    {
        $this->beConstructedWith($carrier, $adapter, $dispatcher);
    }

    /**
     * @param Stampie\Event\SendMessageEvent $event
     * @param Stampie\Identity $identity
     * @param Stampie\Message $message
     * @param Stampie\Request $request
     * @param Stampie\Response $response
     */
    function it_dispatches_event_sends_request($event, $identity, $message, $request, $response, $dispatcher, $carrier, $adapter)
    {
        $dispatcher->dispatch(StampieEvents::SEND, Argument::type('Stampie\Event\SendMessageEvent'))->shouldBeCalled()->willReturn($event);

        $event->getTo()->willReturn($identity);
        $event->getMessage()->willReturn($message);
        $event->isDefaultPrevented()->willReturn(false);

        $carrier->createRequest($identity, $message)->shouldBeCalled()->willReturn($request);
        $carrier->handleResponse($response)->willReturn('my-message-id');

        $adapter->request($request)->shouldBeCalled()->willReturn($response);

        $this->send($identity, $message)->shouldBeLike(new MessageHeader('my-message-id'));
    }

    /**
     * @param Stampie\Event\SendMessageEvent $event
     * @param Stampie\Identity $identity
     * @param Stampie\Message $message
     */
    function it_skips_calling_carrier_when_defaut_prevented($event, $identity, $message, $dispatcher, $carrier)
    {
        $dispatcher->dispatch(Argument::any(), Argument::any())->willReturn($event);

        $event->isDefaultPrevented()->shouldBeCalled()->willReturn(true);
        $event->getTo()->willReturn($identity);

        $carrier->createRequest($identity, $message)->shouldNotBeCalled();

        $this->send($identity, $message)->shouldBeLike(new MessageHeader(null));
    }

    /**
     * @param Stampie\Event\SendMessageEvent $event
     * @param Stampie\Identity $identity
     * @param Stampie\Message $message
     * @param Stampie\Request $request
     * @param Stampie\Response $response
     */
    function it_dispatches_failed_event_when_carrier_raises_exception($event, $identity, $message, $request, $response, $dispatcher, $carrier, $adapter)
    {
        $event->getTo()->willReturn($identity);
        $event->getMessage()->willReturn($message);
        $event->isDefaultPrevented()->willReturn(false);

        $dispatcher->dispatch(StampieEvents::SEND, Argument::any())->willReturn($event);
        $dispatcher->dispatch(StampieEvents::FAILED, Argument::type('Stampie\Event\FailedMessageEvent'))->shouldBeCalled();

        $adapter->request($request)->willReturn($response);

        $carrier->createRequest($identity, $message)->willReturn($request);
        $carrier->handleResponse($response)->willThrow('RuntimeException');

        $this->shouldThrow('RuntimeException')->duringSend($identity, $message);
    }
}
