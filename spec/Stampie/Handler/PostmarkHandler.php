<?php

namespace spec\Stampie\Handler;

use PHPSpec2\ObjectBehavior;
use Stampie\Message\Identity;

class PostmarkHandler extends ObjectBehavior
{
    /**
     * @param Stampie\Adapter\AdapterInterface $adapter
     * @param Stampie\Message\Message $message
     */
    function let($adapter, $message)
    {
        $message->getFrom()->willReturn(new Identity('henrik@bjrnskov.dk'));
        $message->getHeaders()->willReturn(array());

        $this->beConstructedWith($adapter, 'my-personal-key');
    }

    function it_prepares_the_request_before_calling_the_adapter($message)
    {
        $this->prepare(\Mockery::type('Stampie\Adapter\Request'))->shouldBeCalled();

        $this->send(new Identity('henrik@bjrnskov.dk'), $message);
    }

    function it_calls_the_adapter_with_a_request($adapter, $message)
    {
        $adapter->call(\Mockery::type('Stampie\Adapter\Request'))->shouldBeCalled();

        $this->send(new Identity('henrik@bjrnskov.dk'), $message);
    }

    /**
     * @param Stampie\Adapter\Request $request
     */
    function it_sets_the_right_headers_when_preparing_request($request)
    {
        $request->setHeaders(array(
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'X-Postmark-Server-Token' => 'my-personal-key',
        ))->shouldBeCalled();

        $this->prepare($request);
    }
}
