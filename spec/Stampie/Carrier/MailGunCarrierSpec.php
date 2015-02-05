<?php

namespace spec\Stampie\Carrier;

use Prophecy\Argument;
use Stampie\Message\MessageHeader;

class MailGunCarrierSpec extends \PhpSpec\ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('my-domain:my-token');
    }

    /**
     * @param Stampie\Recipient $recipient
     * @param Stampie\Message   $message
     */
    function it_creates_a_request_with_formatted_message($recipient, $message)
    {
        $recipient->formatAsAddress()->willReturn('contact@sbin.dk');

        $request = $this->createRequest($recipient, $message);
        $request->getUrl()->shouldReturn('https://api.mailgun.net/v2/my-domain/messages');
        $request->getHeaders()->shouldReturn([
            'Authorization' => 'Basic YXBpOm15LXRva2Vu',
        ]);

        $request
            ->getContent()
            ->shouldReturn('to=contact%40sbin.dk')
        ;

        $request->shouldBeAnInstanceOf('Stampie\Request');
    }

    /**
     * @param Stampie\Response $response
     */
    function it_throws_an_exception_when_response_is_not_successful($response)
    {
        $response->isSuccessful()->willReturn(false);

        $this->shouldThrow('LogicException')->duringHandleResponse($response);
    }
}
