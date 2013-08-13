<?php

namespace spec\Stampie\Handler;

use Prophecy\Argument;

class PostmarkHandler extends \PhpSpec\ObjectBehavior
{
    /**
     * @param Stampie\Adapter $adapter
     * @param Stampie\Message $message
     * @param Stampie\Identity $identity
     */
    function let($adapter, $message, $identity)
    {
        $this->beConstructedWith($adapter, 'my-personal-key');
    }

    /**
     * @param Stampie\Adapter\Response $response
     */
    function it_calls_the_adapter_with_a_request($response, $adapter, $message, $identity)
    {
        $message->getFrom()->willReturn($identity);
        $message->getHeaders()->willReturn(array());

        $response->isUnauthorized()->willReturn(false);

        $adapter->request(Argument::type('Stampie\Adapter\Request'))->shouldBeCalled()->willReturn($response);

        $this->send($identity, $message);
    }

    /**
     * @param Stampie\Adapter\Response $response
     */
    function it_throws_an_exception_when_response_is_unauthorized($response, $adapter, $message, $identity)
    {
        $message->getFrom()->willReturn($identity);
        $message->getHeaders()->willReturn(array());

        $adapter->request(Argument::any())->willReturn($response);

        $response->isUnauthorized()->willReturn(true);

        $this->shouldThrow('Stampie\Exception\UnauthorizedException')->duringSend(new Identity('henrik@bjrnskov.dk'), $message);
    }
}
