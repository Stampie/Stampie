<?php

namespace spec\Stampie\Carrier;

use Prophecy\Argument;
use Stampie\Message\MessageHeader;

class PostmarkCarrierSpec extends \PhpSpec\ObjectBehavior
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
        $identity->__toString()->willReturn('henrik@bjrnskov.dk');

        $response->isSuccessful()->willReturn(true);
        $response->getContent()->willReturn('{"MessageID" : "This is the MessageID" }');

        $adapter->request(Argument::type('Stampie\Adapter\Request'))->shouldBeCalled()->willReturn($response);

        $this->send($identity, $message);
    }

    /**
     * @param Stampie\Adapter\Response $response
     */
    function it_throws_an_exception_when_response_is_unauthorized($response, $adapter, $message, $identity)
    {
        $identity->__toString()->willReturn('henrik@bjrnskov.dk');

        $adapter->request(Argument::type('Stampie\Adapter\Request'))->willReturn($response);

        $response->isSuccessful()->willReturn(false);
        $response->isUnauthorized()->willReturn(true);

        $this->shouldThrow('Stampie\Exception\UnauthorizedException')->duringSend($identity, $message);
    }
}
