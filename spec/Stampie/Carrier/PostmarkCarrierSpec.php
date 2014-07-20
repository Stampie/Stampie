<?php

namespace spec\Stampie\Carrier;

use Prophecy\Argument;
use Stampie\Request;

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

        $request = $this->createRequest($identity, $message);
        $request->getUrl()->shouldReturn('http://api.postmarkapp.com/email');
        $request->getHeaders()->shouldReturn([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'X-Postmark-Server-Token' => 'my-personal-key',
        ]);

        $request->getContent()
            ->shouldReturn('{"To":"henrik@bjrnskov.dk","From":"","Subject":null,"HtmlBody":null,"TextBody":null,"Headers":null}');
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
