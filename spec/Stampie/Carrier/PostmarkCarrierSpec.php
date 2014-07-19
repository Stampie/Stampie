<?php

namespace spec\Stampie\Carrier;

use Prophecy\Argument;
use Stampie\Message\MessageHeader;

class PostmarkCarrierSpec extends \PhpSpec\ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('my-personal-key');
    }

    /**
     * @param Stampie\Message $message
     * @param Stampie\Identity $identity
     */
    function it_creates_a_request_with_formatted_message($message, $identity)
    {
        $identity->__toString()->willReturn('henrik@bjrnskov.dk');

        $this->createRequest($identity, $message)->shouldBeAnInstanceOf('Stampie\Request');
    }

    /**
     * @param Stampie\Response $response
     */
    function it_throws_an_exception_when_response_is_unauthorized($response)
    {
        $response->isSuccessful()->willReturn(false);
        $response->isUnauthorized()->willReturn(true);

        $this->shouldThrow('Stampie\Exception\UnauthorizedException')->duringHandleResponse($response);
    }
}
